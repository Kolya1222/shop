<?php

namespace roilafx\Shop\Console\Commands;

use Illuminate\Console\Command;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvar;
use EvolutionCMS\Models\SiteTmplvarContentvalue;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Models\ClosureTable;
use EvolutionCMS\Models\SiteHtmlsnippet;
use EvolutionCMS\Models\SiteModule;
use EvolutionCMS\Models\SiteModuleAccess;
use EvolutionCMS\Models\SiteModuleDepobj;
use EvolutionCMS\Models\SitePlugin;
use EvolutionCMS\Models\SiteSnippet;
use EvolutionCMS\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportSiteStructure extends Command
{
    protected $signature = 'site:full-import
                            {file? : Путь к JSON файлу импорта}
                            {--parent=0 : Родительская папка для импорта ресурсов}
                            {--template= : ID шаблона по умолчанию для ресурсов}
                            {--publish=1 : Опубликовать ресурсы (1/0)}
                            {--dry-run : Предварительный просмотр без изменений}
                            {--update : Обновлять существующие элементы}
                            {--preserve-ids : Сохранять оригинальные ID}
                            {--clear-first : Очистить таблицы перед импортом}
                            {--import-resources : Импортировать ресурсы}
                            {--import-templates : Импортировать шаблоны}
                            {--import-tv : Импортировать TV параметры}
                            {--import-chunks : Импортировать чанки}
                            {--import-snippets : Импортировать сниппеты}
                            {--import-plugins : Импортировать плагины}
                            {--import-modules : Импортировать модули}
                            {--import-categories : Импортировать категории}
                            {--import-closure : Импортировать связи Closure}
                            {--import-commerce : Импортировать данные Commerce}
                            {--import-evosearch : Импортировать данные EvoSearch}
                            {--import-list-tv : Импортировать данные ListTV}
                            {--all : Импортировать всё}';

    protected $description = 'Полный импорт структуры сайта из JSON файла';

    private array $idMapping = [
        'resources' => [],
        'templates' => [],
        'tvs' => [],
        'chunks' => [],
        'snippets' => [],
        'plugins' => [],
        'modules' => [],
        'categories' => []
    ];

    private array $closureRelations = [];
    private array $statistics = [];
    private array $errors = [];
    private string $prefix = '';

    public function handle()
    {
        $this->prefix = evo()->getConfig('table_prefix', 'k2ku_');

        $this->statistics = [
            'resources' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
            'templates' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
            'tvs' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
            'tv_values' => 0,
            'chunks' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
            'snippets' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
            'plugins' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
            'modules' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
            'categories' => ['created' => 0, 'updated' => 0, 'skipped' => 0],
            'closure_relations' => 0,
            'commerce_currency' => 0,
            'commerce_order_statuses' => 0,
            'commerce_orders' => 0,
            'commerce_order_products' => 0,
            'commerce_order_history' => 0,
            'commerce_order_payments' => 0,
            'evosearch' => 0,
            'list_categories' => 0,
            'list_values' => 0
        ];

        $filePath = $this->argument('file');

        if (!$filePath) {
            $filePath = $this->askForFilePath();
        }

        if (!$this->validateFile($filePath)) {
            return Command::FAILURE;
        }

        $data = $this->loadFromJson($filePath);

        if (empty($data)) {
            $this->error('Не удалось загрузить данные из файла');
            return Command::FAILURE;
        }

        $this->info('Файл загружен успешно');
        $this->info('Дата экспорта: ' . ($data['meta']['export_date'] ?? 'неизвестно'));
        $this->info('Версия Evolution: ' . ($data['meta']['evolution_version'] ?? 'неизвестно'));

        if ($this->option('dry-run')) {
            return $this->showPreview($data);
        }

        return $this->importData($data);
    }

    /**
     * Запрос пути к файлу
     */
    private function askForFilePath(): string
    {
        $importPath = MODX_BASE_PATH . 'assets/import/';

        $this->info('Доступные JSON файлы в /assets/import/:');

        $files = [];
        if (is_dir($importPath)) {
            $allFiles = scandir($importPath);
            $files = array_filter($allFiles, function ($file) use ($importPath) {
                if ($file === '.' || $file === '..') return false;
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                return $ext === 'json' && is_file($importPath . $file);
            });
            $files = array_values($files);
        }

        if (empty($files)) {
            $this->error('Папка /assets/import/ пуста или нет JSON файлов');
            exit(1);
        }

        foreach ($files as $index => $file) {
            $this->line("[$index] " . $file);
        }

        $choice = $this->ask('Выберите номер файла или укажите полный путь');

        if (is_numeric($choice) && isset($files[$choice])) {
            return $importPath . $files[$choice];
        }

        return $choice;
    }

    /**
     * Валидация файла
     */
    private function validateFile(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            $this->error("Файл не найден: {$filePath}");
            return false;
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($extension !== 'json') {
            $this->error("Поддерживаются только JSON файлы");
            return false;
        }

        return true;
    }

    /**
     * Загрузка из JSON
     */
    private function loadFromJson(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Ошибка парсинга JSON: ' . json_last_error_msg());
            return [];
        }

        return $data;
    }

    /**
     * Предварительный просмотр
     */
    private function showPreview(array $data): int
    {
        $this->info('=== ПРЕДВАРИТЕЛЬНЫЙ ПРОСМОТР ===');

        $stats = $this->analyzeData($data);

        $this->info('Будет импортировано:');

        $rows = [
            ['Категории', $stats['categories']['total'], $stats['categories']['create'], $stats['categories']['update']],
            ['Шаблоны', $stats['templates']['total'], $stats['templates']['create'], $stats['templates']['update']],
            ['TV параметры', $stats['tvs']['total'], $stats['tvs']['create'], $stats['tvs']['update']],
            ['Ресурсы', $stats['resources']['total'], $stats['resources']['create'], $stats['resources']['update']],
            ['Чанки', $stats['chunks']['total'], $stats['chunks']['create'], $stats['chunks']['update']],
            ['Сниппеты', $stats['snippets']['total'], $stats['snippets']['create'], $stats['snippets']['update']],
            ['Плагины', $stats['plugins']['total'], $stats['plugins']['create'], $stats['plugins']['update']],
            ['Модули', $stats['modules']['total'], $stats['modules']['create'], $stats['modules']['update']],
        ];

        // Добавляем Commerce если есть
        if ($stats['commerce_currency']['total'] > 0) {
            $rows[] = ['Commerce валюты', $stats['commerce_currency']['total'], $stats['commerce_currency']['create'], $stats['commerce_currency']['update']];
        }
        if ($stats['commerce_order_statuses']['total'] > 0) {
            $rows[] = ['Commerce статусы', $stats['commerce_order_statuses']['total'], $stats['commerce_order_statuses']['create'], $stats['commerce_order_statuses']['update']];
        }
        if ($stats['commerce_orders']['total'] > 0) {
            $rows[] = ['Commerce заказы', $stats['commerce_orders']['total'], $stats['commerce_orders']['create'], $stats['commerce_orders']['update']];
        }

        // Добавляем EvoSearch если есть
        if ($stats['evosearch']['total'] > 0) {
            $rows[] = ['EvoSearch записи', $stats['evosearch']['total'], $stats['evosearch']['create'], $stats['evosearch']['update']];
        }

        // Добавляем ListTV если есть
        if ($stats['list_categories']['total'] > 0) {
            $rows[] = ['ListTV категории', $stats['list_categories']['total'], $stats['list_categories']['create'], $stats['list_categories']['update']];
        }
        if ($stats['list_values']['total'] > 0) {
            $rows[] = ['ListTV значения', $stats['list_values']['total'], $stats['list_values']['create'], $stats['list_values']['update']];
        }

        $this->table(['Элемент', 'Всего', 'Создано', 'Обновлено'], $rows);

        $this->info('Значений TV для импорта: ' . $stats['tv_values']);
        $this->info('Связей Closure для восстановления: ' . $stats['closure_relations']);

        $this->info('Параметры импорта:');
        $this->info('  Родительская папка: ' . $this->option('parent'));
        $this->info('  Сохранять ID: ' . ($this->option('preserve-ids') ? 'Да' : 'Нет'));
        $this->info('  Обновлять существующие: ' . ($this->option('update') ? 'Да' : 'Нет'));
        $this->info('  Очистить перед импортом: ' . ($this->option('clear-first') ? 'Да' : 'Нет'));

        if (!$this->confirm('Продолжить импорт?')) {
            $this->info('Импорт отменен');
            return Command::SUCCESS;
        }

        return $this->importData($data);
    }

    /**
     * Анализ данных для предпросмотра
     */
    private function analyzeData(array $data): array
    {
        $result = [
            'categories' => ['total' => 0, 'create' => 0, 'update' => 0],
            'templates' => ['total' => 0, 'create' => 0, 'update' => 0],
            'tvs' => ['total' => 0, 'create' => 0, 'update' => 0],
            'resources' => ['total' => 0, 'create' => 0, 'update' => 0],
            'chunks' => ['total' => 0, 'create' => 0, 'update' => 0],
            'snippets' => ['total' => 0, 'create' => 0, 'update' => 0],
            'plugins' => ['total' => 0, 'create' => 0, 'update' => 0],
            'modules' => ['total' => 0, 'create' => 0, 'update' => 0],
            'commerce_currency' => ['total' => 0, 'create' => 0, 'update' => 0],
            'commerce_order_statuses' => ['total' => 0, 'create' => 0, 'update' => 0],
            'commerce_orders' => ['total' => 0, 'create' => 0, 'update' => 0],
            'commerce_order_products' => ['total' => 0, 'create' => 0, 'update' => 0],
            'commerce_order_history' => ['total' => 0, 'create' => 0, 'update' => 0],
            'commerce_order_payments' => ['total' => 0, 'create' => 0, 'update' => 0],
            'evosearch' => ['total' => 0, 'create' => 0, 'update' => 0],
            'list_categories' => ['total' => 0, 'create' => 0, 'update' => 0],
            'list_values' => ['total' => 0, 'create' => 0, 'update' => 0],
            'tv_values' => 0,
            'closure_relations' => 0
        ];

        $importAll = $this->option('all');
        $updateMode = $this->option('update');

        // Категории
        if ($importAll || $this->option('import-categories')) {
            if (isset($data['data']['categories'])) {
                $result['categories']['total'] = count($data['data']['categories']);
                foreach ($data['data']['categories'] as $item) {
                    if ($updateMode && Category::find($item['id'])) {
                        $result['categories']['update']++;
                    } else {
                        $result['categories']['create']++;
                    }
                }
            }
        }

        // Шаблоны
        if ($importAll || $this->option('import-templates')) {
            if (isset($data['data']['templates'])) {
                $result['templates']['total'] = count($data['data']['templates']);
                foreach ($data['data']['templates'] as $item) {
                    if ($updateMode && SiteTemplate::find($item['id'])) {
                        $result['templates']['update']++;
                    } else {
                        $result['templates']['create']++;
                    }
                }
            }
        }

        // TV параметры
        if ($importAll || $this->option('import-tv')) {
            if (isset($data['data']['tvs'])) {
                $result['tvs']['total'] = count($data['data']['tvs']);
                foreach ($data['data']['tvs'] as $item) {
                    if ($updateMode && SiteTmplvar::find($item['id'])) {
                        $result['tvs']['update']++;
                    } else {
                        $result['tvs']['create']++;
                    }
                }
            }
        }

        // Ресурсы
        if ($importAll || $this->option('import-resources')) {
            if (isset($data['data']['resources'])) {
                $resourceStats = $this->analyzeResources($data['data']['resources'], $updateMode);
                $result['resources'] = $resourceStats;
                $result['tv_values'] = $resourceStats['tv_values'] ?? 0;
            }
        }

        // Closure relations
        if (isset($data['closure_relations'])) {
            $result['closure_relations'] = count($data['closure_relations']);
        }

        // Чанки
        if ($importAll || $this->option('import-chunks')) {
            if (isset($data['data']['chunks'])) {
                $result['chunks']['total'] = count($data['data']['chunks']);
                foreach ($data['data']['chunks'] as $item) {
                    if ($updateMode && SiteHtmlsnippet::find($item['id'])) {
                        $result['chunks']['update']++;
                    } else {
                        $result['chunks']['create']++;
                    }
                }
            }
        }

        // Сниппеты
        if ($importAll || $this->option('import-snippets')) {
            if (isset($data['data']['snippets'])) {
                $result['snippets']['total'] = count($data['data']['snippets']);
                foreach ($data['data']['snippets'] as $item) {
                    if ($updateMode && SiteSnippet::find($item['id'])) {
                        $result['snippets']['update']++;
                    } else {
                        $result['snippets']['create']++;
                    }
                }
            }
        }

        // Плагины
        if ($importAll || $this->option('import-plugins')) {
            if (isset($data['data']['plugins'])) {
                $result['plugins']['total'] = count($data['data']['plugins']);
                foreach ($data['data']['plugins'] as $item) {
                    if ($updateMode && SitePlugin::find($item['id'])) {
                        $result['plugins']['update']++;
                    } else {
                        $result['plugins']['create']++;
                    }
                }
            }
        }

        // Модули
        if ($importAll || $this->option('import-modules')) {
            if (isset($data['data']['modules'])) {
                $result['modules']['total'] = count($data['data']['modules']);
                foreach ($data['data']['modules'] as $item) {
                    if ($updateMode && SiteModule::find($item['id'])) {
                        $result['modules']['update']++;
                    } else {
                        $result['modules']['create']++;
                    }
                }
            }
        }

        // Commerce данные
        if ($importAll || $this->option('import-commerce')) {
            if (isset($data['data']['commerce'])) {
                $commerce = $data['data']['commerce'];

                // Валюты
                if (isset($commerce['currency'])) {
                    $result['commerce_currency']['total'] = count($commerce['currency']);
                    foreach ($commerce['currency'] as $item) {
                        $item = (array)$item;
                        // Проверяем существование конкретной записи по ID
                        if ($updateMode && isset($item['id']) && $item['id'] && DB::table('commerce_currency')->where('id', $item['id'])->exists()) {
                            $result['commerce_currency']['update']++;
                        } else {
                            $result['commerce_currency']['create']++;
                        }
                    }
                }

                // Статусы заказов
                if (isset($commerce['order_statuses'])) {
                    $result['commerce_order_statuses']['total'] = count($commerce['order_statuses']);
                    foreach ($commerce['order_statuses'] as $item) {
                        $item = (array)$item;
                        // Проверяем существование конкретной записи по ID
                        if ($updateMode && isset($item['id']) && $item['id'] && DB::table('commerce_order_statuses')->where('id', $item['id'])->exists()) {
                            $result['commerce_order_statuses']['update']++;
                        } else {
                            $result['commerce_order_statuses']['create']++;
                        }
                    }
                }

                // Заказы
                if (isset($commerce['orders'])) {
                    $result['commerce_orders']['total'] = count($commerce['orders']);
                    foreach ($commerce['orders'] as $item) {
                        $item = (array)$item;
                        if ($updateMode && isset($item['id']) && $item['id'] && DB::table('commerce_orders')->where('id', $item['id'])->exists()) {
                            $result['commerce_orders']['update']++;
                        } else {
                            $result['commerce_orders']['create']++;
                        }
                    }
                }

                // Товары в заказах
                if (isset($commerce['order_products'])) {
                    $result['commerce_order_products']['total'] = count($commerce['order_products']);
                    foreach ($commerce['order_products'] as $item) {
                        $item = (array)$item;
                        if ($updateMode && isset($item['id']) && $item['id'] && DB::table('commerce_order_products')->where('id', $item['id'])->exists()) {
                            $result['commerce_order_products']['update']++;
                        } else {
                            $result['commerce_order_products']['create']++;
                        }
                    }
                }

                // История заказов
                if (isset($commerce['order_history'])) {
                    $result['commerce_order_history']['total'] = count($commerce['order_history']);
                    foreach ($commerce['order_history'] as $item) {
                        $item = (array)$item;
                        if ($updateMode && isset($item['id']) && $item['id'] && DB::table('commerce_order_history')->where('id', $item['id'])->exists()) {
                            $result['commerce_order_history']['update']++;
                        } else {
                            $result['commerce_order_history']['create']++;
                        }
                    }
                }

                // Платежи
                if (isset($commerce['order_payments'])) {
                    $result['commerce_order_payments']['total'] = count($commerce['order_payments']);
                    foreach ($commerce['order_payments'] as $item) {
                        $item = (array)$item;
                        if ($updateMode && isset($item['id']) && $item['id'] && DB::table('commerce_order_payments')->where('id', $item['id'])->exists()) {
                            $result['commerce_order_payments']['update']++;
                        } else {
                            $result['commerce_order_payments']['create']++;
                        }
                    }
                }
            }
        }

        // EvoSearch данные
        if ($importAll || $this->option('import-evosearch')) {
            if (isset($data['data']['evosearch'])) {
                $result['evosearch']['total'] = count($data['data']['evosearch']);

                foreach ($data['data']['evosearch'] as $item) {
                    $item = (array)$item;
                    $exists = false;

                    // Проверяем существование записи
                    if (isset($item['id']) && $item['id']) {
                        $exists = DB::table('evosearch_table')->where('id', $item['id'])->exists();
                    } elseif (isset($item['docid']) && $item['docid']) {
                        $exists = DB::table('evosearch_table')->where('docid', $item['docid'])->exists();
                    }

                    if ($updateMode && $exists) {
                        $result['evosearch']['update']++;
                    } else {
                        $result['evosearch']['create']++;
                    }
                }
            }
        }

        // ListTV данные
        if ($importAll || $this->option('import-list-tv')) {
            if (isset($data['data']['list_tv'])) {
                // Категории ListTV
                if (isset($data['data']['list_tv']['categories'])) {
                    $result['list_categories']['total'] = count($data['data']['list_tv']['categories']);

                    foreach ($data['data']['list_tv']['categories'] as $item) {
                        $item = (array)$item;
                        $exists = false;

                        if (isset($item['id']) && $item['id']) {
                            $exists = DB::table('list_catagory_table')->where('id', $item['id'])->exists();
                        } elseif (isset($item['name'])) {
                            $exists = DB::table('list_catagory_table')->where('name', $item['name'])->exists();
                        }

                        if ($updateMode && $exists) {
                            $result['list_categories']['update']++;
                        } else {
                            $result['list_categories']['create']++;
                        }
                    }
                }

                // Значения ListTV
                if (isset($data['data']['list_tv']['values'])) {
                    $result['list_values']['total'] = count($data['data']['list_tv']['values']);

                    foreach ($data['data']['list_tv']['values'] as $item) {
                        $item = (array)$item;
                        $exists = false;

                        if (isset($item['id']) && $item['id']) {
                            $exists = DB::table('list_value_table')->where('id', $item['id'])->exists();
                        }

                        if ($updateMode && $exists) {
                            $result['list_values']['update']++;
                        } else {
                            $result['list_values']['create']++;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Проверка существования таблицы
     */
    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    /**
     * Анализ ресурсов
     */
    private function analyzeResources(array $resources, bool $updateMode, array &$result = null): array
    {
        if ($result === null) {
            $result = ['total' => 0, 'create' => 0, 'update' => 0, 'tv_values' => 0, 'closure_relations' => 0];
        }

        foreach ($resources as $item) {
            $result['total']++;

            if (isset($item['tv'])) {
                $result['tv_values'] += count($item['tv']);
            }

            if ($updateMode && isset($item['id']) && SiteContent::find($item['id'])) {
                $result['update']++;
            } else {
                $result['create']++;
            }

            if (!empty($item['children'])) {
                $this->analyzeResources($item['children'], $updateMode, $result);
            }
        }

        return $result;
    }

    /**
     * Импорт данных
     */
    private function importData(array $data): int
    {
        $importAll = $this->option('all');

        // Загружаем отдельный блок closure_relations, если он есть
        if (isset($data['closure_relations'])) {
            $this->closureRelations = $data['closure_relations'];
            $this->info("Загружено связей Closure из отдельного блока: " . count($this->closureRelations));
        }

        DB::beginTransaction();

        try {
            // Очистка таблиц если нужно
            if ($this->option('clear-first')) {
                $this->clearTables($importAll);
            }

            // Импорт в правильном порядке (с учетом зависимостей)

            // 1. Категории
            if ($importAll || $this->option('import-categories')) {
                $this->importCategories($data['data']['categories'] ?? []);
            }

            // 2. Шаблоны
            if ($importAll || $this->option('import-templates')) {
                $this->importTemplates($data['data']['templates'] ?? []);
            }

            // 3. TV параметры
            if ($importAll || $this->option('import-tv')) {
                $this->importTVs($data['data']['tvs'] ?? []);
            }

            // 4. Чанки
            if ($importAll || $this->option('import-chunks')) {
                $this->importChunks($data['data']['chunks'] ?? []);
            }

            // 5. Сниппеты
            if ($importAll || $this->option('import-snippets')) {
                $this->importSnippets($data['data']['snippets'] ?? []);
            }

            // 6. Плагины
            if ($importAll || $this->option('import-plugins')) {
                $this->importPlugins($data['data']['plugins'] ?? []);
            }

            // 7. Модули
            if ($importAll || $this->option('import-modules')) {
                $this->importModules($data['data']['modules'] ?? []);
            }

            // 8. Ресурсы (самое сложное - в конце)
            if ($importAll || $this->option('import-resources')) {
                $this->importResources($data['data']['resources'] ?? []);
            }

            // 9. Восстановление связей Closure
            if ($importAll || $this->option('import-closure')) {
                $this->restoreClosureRelations();
            }

            // 10. Commerce данные
            if ($importAll || $this->option('import-commerce')) {
                $this->importCommerce($data['data']['commerce'] ?? []);
            }

            // 11. EvoSearch данные
            if ($importAll || $this->option('import-evosearch')) {
                $this->importEvoSearch($data['data']['evosearch'] ?? []);
            }

            // 12. ListTV данные
            if ($importAll || $this->option('import-list-tv')) {
                $this->importListTV($data['data']['list_tv'] ?? []);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Ошибка при импорте: ' . $e->getMessage());
            $this->error('Стек трассировки: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }

        $this->displayImportStatistics();

        if (!empty($this->errors)) {
            $this->warn('Импорт завершен с ошибками:');
            foreach ($this->errors as $error) {
                $this->error('  - ' . $error);
            }
            return Command::FAILURE;
        }

        $this->info('Импорт успешно завершен!');
        return Command::SUCCESS;
    }

    /**
     * Очистка таблиц
     */
    private function clearTables(bool $importAll): void
    {
        $this->info('Очистка таблиц перед импортом...');

        $tables = [];

        if ($importAll || $this->option('import-closure')) {
            $tables[] = 'site_content_closure';
        }

        if ($importAll || $this->option('import-resources')) {
            $tables[] = 'site_content';
            $tables[] = 'site_tmplvar_contentvalues';
        }

        if ($importAll || $this->option('import-tv')) {
            $tables[] = 'site_tmplvar_templates';
            $tables[] = 'site_tmplvars';
        }

        if ($importAll || $this->option('import-templates')) {
            $tables[] = 'site_templates';
        }

        if ($importAll || $this->option('import-chunks')) {
            $tables[] = 'site_htmlsnippets';
        }

        if ($importAll || $this->option('import-snippets')) {
            $tables[] = 'site_snippets';
        }

        if ($importAll || $this->option('import-plugins')) {
            $tables[] = 'site_plugins';
            $tables[] = 'site_plugin_events';
        }

        if ($importAll || $this->option('import-modules')) {
            $tables[] = 'site_modules';
            $tables[] = 'site_module_access';
            $tables[] = 'site_module_depobj';
        }

        if ($importAll || $this->option('import-categories')) {
            $tables[] = 'categories';
        }

        if ($importAll || $this->option('import-commerce')) {
            $tables[] = 'commerce_currency';
            $tables[] = 'commerce_order_statuses';
            $tables[] = 'commerce_orders';
            $tables[] = 'commerce_order_products';
            $tables[] = 'commerce_order_history';
            $tables[] = 'commerce_order_payments';
        }

        if ($importAll || $this->option('import-evosearch')) {
            $tables[] = 'evosearch_table';
        }

        if ($importAll || $this->option('import-list-tv')) {
            $tables[] = 'list_catagory_table';
            $tables[] = 'list_value_table';
        }

        // Отключаем проверку внешних ключей временно
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->line("  Очищена таблица: " . str_replace($this->prefix, '', $table));
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Импорт категорий
     */
    private function importCategories(array $categories): void
    {
        if (empty($categories)) {
            return;
        }

        $this->info('Импорт категорий...');

        foreach ($categories as $item) {
            try {
                $oldId = $item['id'];
                unset($item['id']);

                $category = null;

                if ($this->option('update')) {
                    $category = Category::find($oldId);
                }

                if ($category) {
                    $category->update($item);
                    $this->statistics['categories']['updated']++;
                    $this->idMapping['categories'][$oldId] = $oldId;
                } else {
                    if ($this->option('preserve-ids')) {
                        $item['id'] = $oldId;
                        $category = Category::create($item);
                    } else {
                        $category = Category::create($item);
                    }
                    $this->statistics['categories']['created']++;
                    $this->idMapping['categories'][$oldId] = $category->id;
                }
            } catch (\Exception $e) {
                $this->errors[] = "Категория '{$item['category']}': " . $e->getMessage();
            }
        }
    }

    /**
     * Импорт шаблонов
     */
    private function importTemplates(array $templates): void
    {
        if (empty($templates)) {
            return;
        }

        $this->info('Импорт шаблонов...');

        foreach ($templates as $item) {
            try {
                $oldId = $item['id'];

                // Маппинг категории
                if (isset($item['category']) && $item['category'] > 0) {
                    $item['category'] = $this->idMapping['categories'][$item['category']] ?? $item['category'];
                }

                unset($item['id']);

                $template = null;

                if ($this->option('update')) {
                    $template = SiteTemplate::find($oldId);
                }

                if ($template) {
                    $template->update($item);
                    $this->statistics['templates']['updated']++;
                    $this->idMapping['templates'][$oldId] = $oldId;
                } else {
                    if ($this->option('preserve-ids')) {
                        $item['id'] = $oldId;
                        $template = SiteTemplate::create($item);
                    } else {
                        $template = SiteTemplate::create($item);
                    }
                    $this->statistics['templates']['created']++;
                    $this->idMapping['templates'][$oldId] = $template->id;
                }
            } catch (\Exception $e) {
                $this->errors[] = "Шаблон '{$item['templatename']}': " . $e->getMessage();
            }
        }
    }

    /**
     * Импорт TV параметров
     */
    private function importTVs(array $tvs): void
    {
        if (empty($tvs)) {
            return;
        }

        $this->info('Импорт TV параметров...');

        foreach ($tvs as $item) {
            try {
                $oldId = $item['id'];
                $templates = $item['templates'] ?? [];
                unset($item['templates'], $item['id']);

                // Маппинг категории
                if (isset($item['category']) && $item['category'] > 0) {
                    $item['category'] = $this->idMapping['categories'][$item['category']] ?? $item['category'];
                }

                $tv = null;

                if ($this->option('update')) {
                    $tv = SiteTmplvar::find($oldId);
                }

                if ($tv) {
                    $tv->update($item);
                    $this->statistics['tvs']['updated']++;
                    $this->idMapping['tvs'][$oldId] = $oldId;
                } else {
                    if ($this->option('preserve-ids')) {
                        $item['id'] = $oldId;
                        $tv = SiteTmplvar::create($item);
                    } else {
                        $tv = SiteTmplvar::create($item);
                    }
                    $this->statistics['tvs']['created']++;
                    $this->idMapping['tvs'][$oldId] = $tv->id;
                }

                // Импорт связей с шаблонами
                if (!empty($templates)) {
                    $this->importTVTemplates($tv->id, $templates);
                }
            } catch (\Exception $e) {
                $this->errors[] = "TV '{$item['name']}': " . $e->getMessage();
            }
        }
    }

    /**
     * Импорт связей TV с шаблонами
     */
    private function importTVTemplates(int $tvId, array $templates): void
    {
        foreach ($templates as $templateId) {
            $newTemplateId = $this->idMapping['templates'][$templateId] ?? $templateId;

            SiteTmplvarTemplate::firstOrCreate([
                'tmplvarid' => $tvId,
                'templateid' => $newTemplateId
            ]);
        }
    }

    /**
     * Импорт чанков
     */
    private function importChunks(array $chunks): void
    {
        if (empty($chunks)) {
            return;
        }

        $this->info('Импорт чанков...');

        foreach ($chunks as $item) {
            try {
                $oldId = $item['id'];

                // Маппинг категории
                if (isset($item['category']) && $item['category'] > 0) {
                    $item['category'] = $this->idMapping['categories'][$item['category']] ?? $item['category'];
                }

                unset($item['id']);

                $chunk = null;

                if ($this->option('update')) {
                    $chunk = SiteHtmlsnippet::find($oldId);
                }

                if ($chunk) {
                    $chunk->update($item);
                    $this->statistics['chunks']['updated']++;
                    $this->idMapping['chunks'][$oldId] = $oldId;
                } else {
                    if ($this->option('preserve-ids')) {
                        $item['id'] = $oldId;
                        $chunk = SiteHtmlsnippet::create($item);
                    } else {
                        $chunk = SiteHtmlsnippet::create($item);
                    }
                    $this->statistics['chunks']['created']++;
                    $this->idMapping['chunks'][$oldId] = $chunk->id;
                }
            } catch (\Exception $e) {
                $this->errors[] = "Чанк '{$item['name']}': " . $e->getMessage();
            }
        }
    }

    /**
     * Импорт сниппетов
     */
    private function importSnippets(array $snippets): void
    {
        if (empty($snippets)) {
            return;
        }

        $this->info('Импорт сниппетов...');

        foreach ($snippets as $item) {
            try {
                $oldId = $item['id'];

                // Маппинг категории
                if (isset($item['category']) && $item['category'] > 0) {
                    $item['category'] = $this->idMapping['categories'][$item['category']] ?? $item['category'];
                }

                unset($item['id']);

                $snippet = null;

                if ($this->option('update')) {
                    $snippet = SiteSnippet::find($oldId);
                }

                if ($snippet) {
                    $snippet->update($item);
                    $this->statistics['snippets']['updated']++;
                    $this->idMapping['snippets'][$oldId] = $oldId;
                } else {
                    if ($this->option('preserve-ids')) {
                        $item['id'] = $oldId;
                        $snippet = SiteSnippet::create($item);
                    } else {
                        $snippet = SiteSnippet::create($item);
                    }
                    $this->statistics['snippets']['created']++;
                    $this->idMapping['snippets'][$oldId] = $snippet->id;
                }
            } catch (\Exception $e) {
                $this->errors[] = "Сниппет '{$item['name']}': " . $e->getMessage();
            }
        }
    }

    /**
     * Импорт плагинов
     */
    private function importPlugins(array $plugins): void
    {
        if (empty($plugins)) {
            return;
        }

        $this->info('Импорт плагинов...');

        foreach ($plugins as $item) {
            try {
                $oldId = $item['id'];
                $events = $item['events'] ?? [];
                unset($item['events'], $item['id']);

                // Маппинг категории
                if (isset($item['category']) && $item['category'] > 0) {
                    $item['category'] = $this->idMapping['categories'][$item['category']] ?? $item['category'];
                }

                $plugin = null;

                if ($this->option('update')) {
                    $plugin = SitePlugin::find($oldId);
                }

                if ($plugin) {
                    $plugin->update($item);
                    $this->statistics['plugins']['updated']++;
                    $this->idMapping['plugins'][$oldId] = $oldId;
                } else {
                    if ($this->option('preserve-ids')) {
                        $item['id'] = $oldId;
                        $plugin = SitePlugin::create($item);
                    } else {
                        $plugin = SitePlugin::create($item);
                    }
                    $this->statistics['plugins']['created']++;
                    $this->idMapping['plugins'][$oldId] = $plugin->id;
                }

                // Импорт событий плагина
                if (!empty($events)) {
                    $this->importPluginEvents($plugin->id, $events);
                }
            } catch (\Exception $e) {
                $this->errors[] = "Плагин '{$item['name']}': " . $e->getMessage();
            }
        }
    }

    /**
     * Импорт событий плагина
     */
    private function importPluginEvents(int $pluginId, array $events): void
    {
        foreach ($events as $event) {
            $eventId = $event['evtid'] ?? null;
            $eventName = $event['name'] ?? null;
            $priority = $event['priority'] ?? 0;

            if (!$eventId && $eventName) {
                $eventId = DB::table('system_eventnames')
                    ->where('name', $eventName)
                    ->value('id');
            }

            if (!$eventId) {
                $this->warn("Событие не найдено, пропускаем");
                continue;
            }

            DB::table('site_plugin_events')->updateOrInsert(
                [
                    'pluginid' => $pluginId,
                    'evtid' => $eventId
                ],
                [
                    'priority' => $priority
                ]
            );
        }
    }

    /**
     * Импорт модулей
     */
    private function importModules(array $modules): void
    {
        if (empty($modules)) {
            return;
        }

        $this->info('Импорт модулей...');

        foreach ($modules as $item) {
            try {
                $oldId = $item['id'];
                $access = $item['access'] ?? [];
                $dependencies = $item['dependencies'] ?? [];
                unset($item['access'], $item['dependencies'], $item['id']);

                // Маппинг категории
                if (isset($item['category']) && $item['category'] > 0) {
                    $item['category'] = $this->idMapping['categories'][$item['category']] ?? $item['category'];
                }

                $module = null;

                if ($this->option('update')) {
                    $module = SiteModule::find($oldId);
                }

                if ($module) {
                    $module->update($item);
                    $this->statistics['modules']['updated']++;
                    $this->idMapping['modules'][$oldId] = $oldId;
                } else {
                    if ($this->option('preserve-ids')) {
                        $item['id'] = $oldId;
                        $module = SiteModule::create($item);
                    } else {
                        $module = SiteModule::create($item);
                    }
                    $this->statistics['modules']['created']++;
                    $this->idMapping['modules'][$oldId] = $module->id;
                }

                // Импорт прав доступа
                if (!empty($access)) {
                    $this->importModuleAccess($module->id, $access);
                }

                // Импорт зависимостей
                if (!empty($dependencies)) {
                    $this->importModuleDependencies($module->id, $dependencies);
                }
            } catch (\Exception $e) {
                $this->errors[] = "Модуль '{$item['name']}': " . $e->getMessage();
            }
        }
    }

    /**
     * Импорт прав доступа модуля
     */
    private function importModuleAccess(int $moduleId, array $access): void
    {
        foreach ($access as $usergroup) {
            SiteModuleAccess::firstOrCreate([
                'module' => $moduleId,
                'usergroup' => $usergroup
            ]);
        }
    }

    /**
     * Импорт зависимостей модуля
     */
    private function importModuleDependencies(int $moduleId, array $dependencies): void
    {
        foreach ($dependencies as $dep) {
            SiteModuleDepobj::firstOrCreate([
                'module' => $moduleId,
                'resource' => $dep['resource'],
                'type' => $dep['type']
            ]);
        }
    }

    /**
     * Импорт данных Commerce
     */
    private function importCommerce(array $data): void
    {
        if (empty($data)) {
            return;
        }

        $this->info('Импорт данных Commerce...');

        // Импорт валют
        if (isset($data['currency']) && !empty($data['currency'])) {
            $this->importCommerceTable('commerce_currency', $data['currency'], 'commerce_currency');
        }

        // Импорт статусов заказов
        if (isset($data['order_statuses']) && !empty($data['order_statuses'])) {
            $this->importCommerceTable('commerce_order_statuses', $data['order_statuses'], 'commerce_order_statuses');
        }

        // Импорт заказов
        if (isset($data['orders']) && !empty($data['orders'])) {
            $this->importCommerceTable('commerce_orders', $data['orders'], 'commerce_orders');
        }

        // Импорт товаров в заказах
        if (isset($data['order_products']) && !empty($data['order_products'])) {
            $this->importCommerceTable('commerce_order_products', $data['order_products'], 'commerce_order_products');
        }

        // Импорт истории заказов
        if (isset($data['order_history']) && !empty($data['order_history'])) {
            $this->importCommerceTable('commerce_order_history', $data['order_history'], 'commerce_order_history');
        }

        // Импорт платежей
        if (isset($data['order_payments']) && !empty($data['order_payments'])) {
            $this->importCommerceTable('commerce_order_payments', $data['order_payments'], 'commerce_order_payments');
        }
    }

    /**
     * Вспомогательный метод для импорта таблиц Commerce
     */
    private function importCommerceTable(string $table, array $items, string $statisticKey): void
    {
        if (!$this->tableExists($table)) {
            $this->warn("  Таблица {$table} не существует, пропускаем");
            return;
        }

        $dbTable = DB::table($table);
        $updated = 0;
        $created = 0;
        $skipped = 0;

        foreach ($items as $item) {
            $item = (array)$item;

            try {
                if (isset($item['id']) && $item['id']) {
                    // Проверяем существование записи по ID
                    $exists = $dbTable->where('id', $item['id'])->exists();

                    if ($exists) {
                        if ($this->option('update')) {
                            // Обновляем существующую запись
                            $dbTable->where('id', $item['id'])->update($item);
                            $updated++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        // Вставляем новую запись
                        $dbTable->insert($item);
                        $created++;
                    }
                } else {
                    // Если нет ID, просто вставляем
                    $dbTable->insert($item);
                    $created++;
                }
            } catch (\Exception $e) {
                // Если ошибка дубликата - пробуем обновить
                if (strpos($e->getMessage(), 'Duplicate entry') !== false && isset($item['id'])) {
                    try {
                        $dbTable->where('id', $item['id'])->update($item);
                        $updated++;
                    } catch (\Exception $e2) {
                        $this->errors[] = "Ошибка при обновлении {$table} (ID: {$item['id']}): " . $e2->getMessage();
                    }
                } else {
                    $this->errors[] = "Ошибка при импорте {$table}: " . $e->getMessage();
                }
            }
        }

        // Сохраняем раздельную статистику
        $this->statistics[$statisticKey . '_created'] = ($this->statistics[$statisticKey . '_created'] ?? 0) + $created;
        $this->statistics[$statisticKey . '_updated'] = ($this->statistics[$statisticKey . '_updated'] ?? 0) + $updated;
        $this->statistics[$statisticKey] = $created + $updated;

        $this->line("  Таблица {$table}: создано {$created}, обновлено {$updated}, пропущено {$skipped}");
    }

    /**
     * Импорт данных EvoSearch
     */
    private function importEvoSearch(array $data): void
    {
        if (empty($data)) {
            return;
        }

        $this->info('Импорт данных EvoSearch...');

        $tableName = 'evosearch_table';
        if (!$this->tableExists($tableName)) {
            $this->warn("  Таблица {$tableName} не существует, пропускаем");
            return;
        }

        $table = DB::table($tableName);
        $updated = 0;
        $created = 0;
        $skipped = 0;

        foreach ($data as $item) {
            $item = (array)$item;

            try {
                // Определяем критерии поиска
                $exists = false;
                $searchCriteria = [];

                if (isset($item['id']) && $item['id']) {
                    $searchCriteria['id'] = $item['id'];
                    $exists = $table->where('id', $item['id'])->exists();
                } elseif (isset($item['docid']) && $item['docid']) {
                    $searchCriteria['docid'] = $item['docid'];
                    $exists = $table->where('docid', $item['docid'])->exists();
                }

                if ($exists) {
                    if ($this->option('update')) {
                        // Обновляем существующую запись
                        $table->where($searchCriteria)->update($item);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                } else {
                    // Вставляем новую запись
                    $table->insert($item);
                    $created++;
                }
            } catch (\Exception $e) {
                // Если ошибка дубликата - пробуем обновить по docid
                if (strpos($e->getMessage(), 'Duplicate entry') !== false && isset($item['docid'])) {
                    try {
                        $table->where('docid', $item['docid'])->update($item);
                        $updated++;
                    } catch (\Exception $e2) {
                        $this->errors[] = "Ошибка при обновлении EvoSearch (docid: {$item['docid']}): " . $e2->getMessage();
                    }
                } else {
                    $this->errors[] = "Ошибка при импорте EvoSearch: " . $e->getMessage();
                }
            }
        }

        // Сохраняем раздельную статистику
        $this->statistics['evosearch_created'] = ($this->statistics['evosearch_created'] ?? 0) + $created;
        $this->statistics['evosearch_updated'] = ($this->statistics['evosearch_updated'] ?? 0) + $updated;
        $this->statistics['evosearch'] = $created + $updated;

        $this->line("  Таблица evosearch_table: создано {$created}, обновлено {$updated}, пропущено {$skipped}");
    }

    /**
     * Импорт данных ListTV
     */
    private function importListTV(array $data): void
    {
        if (empty($data)) {
            return;
        }

        $this->info('Импорт данных ListTV...');

        // Импорт категорий
        if (isset($data['categories']) && !empty($data['categories'])) {
            $this->importListTVTable('list_catagory_table', $data['categories'], 'list_categories');
        }

        // Импорт значений
        if (isset($data['values']) && !empty($data['values'])) {
            $this->importListTVTable('list_value_table', $data['values'], 'list_values');
        }
    }

    /**
     * Вспомогательный метод для импорта таблиц ListTV
     */
    private function importListTVTable(string $table, array $items, string $statisticKey): void
    {
        if (!$this->tableExists($table)) {
            $this->warn("  Таблица {$table} не существует, пропускаем");
            return;
        }

        $dbTable = DB::table($table);
        $updated = 0;
        $created = 0;
        $skipped = 0;

        foreach ($items as $item) {
            $item = (array)$item;

            try {
                if (isset($item['id']) && $item['id']) {
                    // Проверяем по ID
                    $exists = $dbTable->where('id', $item['id'])->exists();

                    if ($exists) {
                        if ($this->option('update')) {
                            $dbTable->where('id', $item['id'])->update($item);
                            $updated++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        $dbTable->insert($item);
                        $created++;
                    }
                } elseif (isset($item['name'])) {
                    // Для категорий проверяем по имени
                    $exists = $dbTable->where('name', $item['name'])->exists();

                    if ($exists) {
                        if ($this->option('update')) {
                            $dbTable->where('name', $item['name'])->update($item);
                            $updated++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        $dbTable->insert($item);
                        $created++;
                    }
                } else {
                    $dbTable->insert($item);
                    $created++;
                }
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false && isset($item['id'])) {
                    try {
                        $dbTable->where('id', $item['id'])->update($item);
                        $updated++;
                    } catch (\Exception $e2) {
                        $this->errors[] = "Ошибка при обновлении {$table}: " . $e2->getMessage();
                    }
                } else {
                    $this->errors[] = "Ошибка при импорте {$table}: " . $e->getMessage();
                }
            }
        }

        // Сохраняем раздельную статистику
        $this->statistics[$statisticKey . '_created'] = ($this->statistics[$statisticKey . '_created'] ?? 0) + $created;
        $this->statistics[$statisticKey . '_updated'] = ($this->statistics[$statisticKey . '_updated'] ?? 0) + $updated;
        $this->statistics[$statisticKey] = $created + $updated;

        $this->line("  Таблица {$table}: создано {$created}, обновлено {$updated}, пропущено {$skipped}");
    }

    /**
     * Импорт ресурсов
     */
    private function importResources(array $resources, int $parent = null): void
    {
        if (empty($resources)) {
            return;
        }

        $importParent = $parent ?? (int)$this->option('parent');
        $defaultTemplate = $this->option('template') ? (int)$this->option('template') : null;
        $published = (bool)$this->option('publish');
        $currentTime = time();

        foreach ($resources as $item) {
            try {
                $oldId = $item['id'] ?? null;
                $tvValues = $item['tv'] ?? [];
                $children = $item['children'] ?? [];

                unset($item['tv'], $item['closure_relations'], $item['children'], $item['id']);

                $item['parent'] = $importParent;

                if (isset($item['template']) && $item['template'] > 0) {
                    $item['template'] = $this->idMapping['templates'][$item['template']] ?? $item['template'];
                } elseif ($defaultTemplate !== null) {
                    $item['template'] = $defaultTemplate;
                }

                $item['createdon'] = $currentTime;
                $item['editedon'] = $currentTime;
                if ($published) {
                    $item['published'] = 1;
                    $item['publishedon'] = $currentTime;
                }

                $resource = null;

                if ($this->option('update') && $oldId) {
                    $resource = SiteContent::find($oldId);
                }

                if ($resource) {
                    $resource->update($item);
                    $this->statistics['resources']['updated']++;
                    $newId = $resource->id;
                    if ($oldId) {
                        $this->idMapping['resources'][$oldId] = $oldId;
                    }
                } else {
                    if ($this->option('preserve-ids') && $oldId) {
                        $item['id'] = $oldId;
                        $resource = SiteContent::create($item);
                    } else {
                        $resource = SiteContent::create($item);
                    }
                    $this->statistics['resources']['created']++;
                    $newId = $resource->id;
                    if ($oldId) {
                        $this->idMapping['resources'][$oldId] = $newId;
                    }
                }

                if (!empty($tvValues)) {
                    $this->importResourceTVs($resource->id, $tvValues);
                    $this->statistics['tv_values'] += count($tvValues);
                }

                if (!empty($children)) {
                    $this->importResources($children, $resource->id);
                }
            } catch (\Exception $e) {
                $this->errors[] = "Ресурс '{$item['pagetitle']}': " . $e->getMessage();
            }
        }
    }

    /**
     * Импорт TV значений ресурса
     */
    private function importResourceTVs(int $resourceId, array $tvValues): void
    {
        foreach ($tvValues as $tvName => $value) {
            $tv = SiteTmplvar::where('name', $tvName)->first();

            if (!$tv) {
                $this->warn("TV '{$tvName}' не найден, значение пропущено");
                continue;
            }

            if ($this->isMultiTV($tv->type) && is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            SiteTmplvarContentvalue::updateOrCreate(
                [
                    'tmplvarid' => $tv->id,
                    'contentid' => $resourceId
                ],
                [
                    'value' => $value
                ]
            );
        }
    }

    /**
     * Восстановление связей Closure
     */
    private function restoreClosureRelations(): void
    {
        if (empty($this->closureRelations)) {
            $this->info('Нет связей Closure для восстановления');
            return;
        }

        $this->info('Восстановление связей Closure Table...');
        $this->info('Всего связей для восстановления: ' . count($this->closureRelations));

        $importedResourceIds = array_values($this->idMapping['resources']);

        if (empty($importedResourceIds)) {
            $this->warn('Нет импортированных ресурсов для восстановления связей');
            return;
        }

        // Определяем правильное имя таблицы
        $tableName = 'k2ku_site_content_closure';

        if (Schema::hasTable($tableName)) {
            $tableName = 'site_content_closure';
        }

        $this->info("Используется таблица: {$tableName}");

        // Очищаем старые связи
        $deleted = 0;
        foreach (array_chunk($importedResourceIds, 100) as $chunk) {
            $ids = implode(',', $chunk);
            $deleted += DB::delete("DELETE FROM {$tableName} WHERE ancestor IN ({$ids}) OR descendant IN ({$ids})");
        }
        $this->info("Удалено {$deleted} старых связей для импортированных ресурсов");

        // Подготавливаем связи с маппингом ID
        $relationsToInsert = [];
        $mappedCount = 0;

        foreach ($this->closureRelations as $relation) {
            $newAncestor = $this->idMapping['resources'][$relation['ancestor']] ?? null;
            $newDescendant = $this->idMapping['resources'][$relation['descendant']] ?? null;

            if ($newAncestor && $newDescendant) {
                $relationsToInsert[] = [
                    'ancestor' => $newAncestor,
                    'descendant' => $newDescendant,
                    'depth' => $relation['depth']
                ];
                $mappedCount++;
            }
        }

        $this->info("Смаппировано {$mappedCount} из " . count($this->closureRelations) . " связей");

        // Удаляем дубликаты
        $uniqueRelations = [];
        foreach ($relationsToInsert as $relation) {
            $key = $relation['ancestor'] . '-' . $relation['descendant'] . '-' . $relation['depth'];
            $uniqueRelations[$key] = $relation;
        }
        $relationsToInsert = array_values($uniqueRelations);

        $this->info("Уникальных связей для вставки: " . count($relationsToInsert));

        // Вставляем пачками
        $inserted = 0;
        foreach (array_chunk($relationsToInsert, 100) as $chunk) {
            $values = [];
            foreach ($chunk as $rel) {
                $values[] = "({$rel['ancestor']}, {$rel['descendant']}, {$rel['depth']})";
            }

            if (!empty($values)) {
                try {
                    $sql = "INSERT IGNORE INTO {$tableName} (ancestor, descendant, depth) VALUES " . implode(',', $values);
                    DB::insert($sql);
                    $inserted += count($values);
                } catch (\Exception $e) {
                    $this->warn("Ошибка при массовой вставке: " . $e->getMessage());
                }
            }
        }

        $this->statistics['closure_relations'] = $inserted;
        $this->info("Вставлено {$inserted} новых связей Closure");
    }

    /**
     * Проверка MultiTV
     */
    private function isMultiTV(string $type): bool
    {
        return in_array($type, ['multitv', 'custom_tv:multitv']);
    }

    /**
     * Отображение статистики импорта
     */
    private function displayImportStatistics(): void
    {
        $this->info('Статистика импорта:');

        $rows = [
            ['Категории', $this->statistics['categories']['created'], $this->statistics['categories']['updated']],
            ['Шаблоны', $this->statistics['templates']['created'], $this->statistics['templates']['updated']],
            ['TV параметры', $this->statistics['tvs']['created'], $this->statistics['tvs']['updated']],
            ['Ресурсы', $this->statistics['resources']['created'], $this->statistics['resources']['updated']],
            ['Чанки', $this->statistics['chunks']['created'], $this->statistics['chunks']['updated']],
            ['Сниппеты', $this->statistics['snippets']['created'], $this->statistics['snippets']['updated']],
            ['Плагины', $this->statistics['plugins']['created'], $this->statistics['plugins']['updated']],
            ['Модули', $this->statistics['modules']['created'], $this->statistics['modules']['updated']],
        ];

        // Добавляем Commerce статистику с разделением
        if (isset($this->statistics['commerce_currency_created']) || isset($this->statistics['commerce_currency_updated'])) {
            $rows[] = [
                'Commerce валюты',
                $this->statistics['commerce_currency_created'] ?? 0,
                $this->statistics['commerce_currency_updated'] ?? 0
            ];
        }

        if (isset($this->statistics['commerce_order_statuses_created']) || isset($this->statistics['commerce_order_statuses_updated'])) {
            $rows[] = [
                'Commerce статусы',
                $this->statistics['commerce_order_statuses_created'] ?? 0,
                $this->statistics['commerce_order_statuses_updated'] ?? 0
            ];
        }

        if (isset($this->statistics['commerce_orders_created']) || isset($this->statistics['commerce_orders_updated'])) {
            $rows[] = [
                'Commerce заказы',
                $this->statistics['commerce_orders_created'] ?? 0,
                $this->statistics['commerce_orders_updated'] ?? 0
            ];
        }

        // Добавляем EvoSearch статистику с разделением
        if (isset($this->statistics['evosearch_created']) || isset($this->statistics['evosearch_updated'])) {
            $rows[] = [
                'EvoSearch записи',
                $this->statistics['evosearch_created'] ?? 0,
                $this->statistics['evosearch_updated'] ?? 0
            ];
        }

        // Добавляем ListTV статистику с разделением
        if (isset($this->statistics['list_categories_created']) || isset($this->statistics['list_categories_updated'])) {
            $rows[] = [
                'ListTV категории',
                $this->statistics['list_categories_created'] ?? 0,
                $this->statistics['list_categories_updated'] ?? 0
            ];
        }

        if (isset($this->statistics['list_values_created']) || isset($this->statistics['list_values_updated'])) {
            $rows[] = [
                'ListTV значения',
                $this->statistics['list_values_created'] ?? 0,
                $this->statistics['list_values_updated'] ?? 0
            ];
        }

        $this->table(['Элемент', 'Создано', 'Обновлено'], $rows);

        $this->info('Значений TV импортировано: ' . $this->statistics['tv_values']);
        $this->info('Связей Closure восстановлено: ' . $this->statistics['closure_relations']);
    }
}
