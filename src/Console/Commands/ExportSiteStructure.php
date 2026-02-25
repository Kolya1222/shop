<?php

namespace roilafx\Shop\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

class ExportSiteStructure extends Command
{
    protected $signature = 'site:full-export
                            {--parent=0 : ID родительского ресурса}
                            {--depth=10 : Максимальная глубина вложенности}
                            {--output=site_export.json : Имя выходного файла}
                            {--with-resources : Экспортировать ресурсы}
                            {--with-templates : Экспортировать шаблоны}
                            {--with-tv : Экспортировать TV параметры}
                            {--with-chunks : Экспортировать чанки}
                            {--with-snippets : Экспортировать сниппеты}
                            {--with-plugins : Экспортировать плагины}
                            {--with-modules : Экспортировать модули}
                            {--with-categories : Экспортировать категории}
                            {--with-closure : Экспортировать связи ClosureTable}
                            {--with-deleted : Включать удаленные ресурсы}
                            {--with-commerce : Экспортировать данные Commerce}
                            {--with-evOSearch : Экспортировать данные EvoSearch}
                            {--with-list-tv : Экспортировать данные ListTV}
                            {--all : Экспортировать всё}
                            {--pretty : Форматировать JSON}';

    protected $description = 'Полный экспорт структуры сайта со всеми элементами';

    private array $statistics = [];
    private string $prefix = '';
    private array $closureRelations = []; // Для хранения всех связей Closure

    public function handle()
    {
        $this->prefix = evo()->getConfig('table_prefix', 'k2ku_');

        $this->statistics = [
            'resources' => 0,
            'templates' => 0,
            'tv' => 0,
            'tv_values' => 0,
            'chunks' => 0,
            'snippets' => 0,
            'plugins' => 0,
            'modules' => 0,
            'categories' => 0,
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

        $outputFile = $this->option('output');
        $exportAll = $this->option('all');

        $this->info('Начинаем полный экспорт структуры сайта...');

        $exportData = [
            'meta' => [
                'export_date' => date('Y-m-d H:i:s'),
                'evolution_version' => evolutionCMS()->getVersionData('version'),
                'parent' => (int)$this->option('parent'),
                'depth' => (int)$this->option('depth'),
                'table_prefix' => $this->prefix
            ],
            'data' => []
        ];

        // Экспорт категорий
        if ($exportAll || $this->option('with-categories')) {
            $exportData['data']['categories'] = $this->exportCategories();
        }

        // Экспорт ресурсов
        if ($exportAll || $this->option('with-resources')) {
            $exportData['data']['resources'] = $this->exportResources();
        }

        // Экспорт шаблонов
        if ($exportAll || $this->option('with-templates')) {
            $exportData['data']['templates'] = $this->exportTemplates();
        }

        // Экспорт TV параметров
        if ($exportAll || $this->option('with-tv')) {
            $exportData['data']['tvs'] = $this->exportTVs();
        }

        // Экспорт чанков
        if ($exportAll || $this->option('with-chunks')) {
            $exportData['data']['chunks'] = $this->exportChunks();
        }

        // Экспорт сниппетов
        if ($exportAll || $this->option('with-snippets')) {
            $exportData['data']['snippets'] = $this->exportSnippets();
        }

        // Экспорт плагинов
        if ($exportAll || $this->option('with-plugins')) {
            $exportData['data']['plugins'] = $this->exportPlugins();
        }

        // Экспорт модулей
        if ($exportAll || $this->option('with-modules')) {
            $exportData['data']['modules'] = $this->exportModules();
        }

        // Экспорт Commerce данных
        if ($exportAll || $this->option('with-commerce')) {
            $exportData['data']['commerce'] = $this->exportCommerce();
        }

        // Экспорт EvoSearch данных
        if ($exportAll || $this->option('with-evosearch')) {
            $exportData['data']['evosearch'] = $this->exportEvoSearch();
        }

        // Экспорт ListTV данных
        if ($exportAll || $this->option('with-list-tv')) {
            $exportData['data']['list_tv'] = $this->exportListTV();
        }

        $filename = $this->saveToFile($exportData, $outputFile);

        $this->displayStatistics();
        $this->info("Экспорт завершен! Файл: {$filename}");

        return Command::SUCCESS;
    }

    /**
     * Экспорт категорий
     */
    private function exportCategories(): array
    {
        $this->info('Экспортируем категории...');

        $categories = Category::orderBy('rank')->get();
        $exportData = [];

        foreach ($categories as $category) {
            $exportData[] = [
                'id' => $category->id,
                'category' => $category->category,
                'rank' => $category->rank
            ];
        }

        $this->statistics['categories'] = count($exportData);
        return $exportData;
    }

    /**
     * Экспорт ресурсов
     */
    private function exportResources(): array
    {
        $this->info('Экспортируем ресурсы...');

        $parent = (int)$this->option('parent');
        $depth = (int)$this->option('depth');
        $withDeleted = $this->option('with-deleted');
        $withClosure = $this->option('with-closure') || $this->option('all');

        // Исправление: убираем лишний параметр $withClosure
        $structure = $this->getResourceStructure($parent, $depth, $withDeleted);

        // Если нужно экспортировать связи Closure
        if ($withClosure) {
            // Получаем ID всех экспортированных ресурсов
            $allResourceIds = $this->getAllResourceIds($structure);

            // Экспортируем ВСЕ связи Closure для этих ресурсов
            $this->closureRelations = $this->exportAllClosureRelations($allResourceIds);
            $this->statistics['closure_relations'] = count($this->closureRelations);

            $this->info("Экспортировано связей Closure: " . count($this->closureRelations));
        }

        // Подсчитываем ресурсы
        $this->statistics['resources'] = $this->countResources($structure);

        return $structure;
    }

    /**
     * Рекурсивное получение структуры ресурсов
     * Исправление: убираем параметр $withClosure, так как связи теперь в отдельном блоке
     */
    private function getResourceStructure(int $parent, int $depth, bool $withDeleted, int $level = 0): array
    {
        if ($level >= $depth) {
            return [];
        }

        $query = SiteContent::where('parent', $parent)
            ->orderBy('menuindex');

        if (!$withDeleted) {
            $query->where('deleted', 0);
        }

        $resources = $query->get();
        $structure = [];

        foreach ($resources as $resource) {
            $item = $resource->toArray();

            // Конвертируем булевы поля
            $booleanFields = [
                'published',
                'isfolder',
                'richtext',
                'searchable',
                'cacheable',
                'deleted',
                'hide_from_tree',
                'privateweb',
                'privatemgr',
                'hidemenu',
                'alias_visible'
            ];

            foreach ($booleanFields as $field) {
                if (isset($item[$field])) {
                    $item[$field] = (bool)$item[$field];
                }
            }

            // Добавляем TV параметры
            $item['tv'] = $this->getResourceTVs($resource->id);
            $this->statistics['tv_values'] += count($item['tv']);

            // Рекурсивно получаем детей
            if ($resource->isfolder) {
                $item['children'] = $this->getResourceStructure(
                    $resource->id,
                    $depth,
                    $withDeleted,
                    $level + 1
                );
            }

            $structure[] = $item;
        }

        return $structure;
    }

    /**
     * Получение всех ID ресурсов
     */
    private function getAllResourceIds(array $structure, array &$ids = []): array
    {
        foreach ($structure as $item) {
            $ids[] = $item['id'];
            if (!empty($item['children'])) {
                $this->getAllResourceIds($item['children'], $ids);
            }
        }
        return $ids;
    }

    /**
     * Экспорт всех связей Closure
     */
    private function exportAllClosureRelations(array $resourceIds): array
    {
        if (empty($resourceIds)) {
            return [];
        }

        // Определяем правильное имя таблицы
        $tableName = 'site_content_closure';
        if (!Schema::hasTable($tableName)) {
            $tableName = 'k2ku_site_content_closure';
        }

        $this->info("  Поиск связей Closure в таблице: {$tableName}");
        $this->info("  Для ресурсов: " . count($resourceIds));

        // Получаем ВСЕ связи, где ancestor ИЛИ descendant в списке ресурсов
        $relations = DB::table($tableName)
            ->whereIn('ancestor', $resourceIds)
            ->orWhereIn('descendant', $resourceIds)
            ->orderBy('ancestor')
            ->orderBy('depth')
            ->get();

        $result = [];
        foreach ($relations as $relation) {
            $result[] = [
                'ancestor' => $relation->ancestor,
                'descendant' => $relation->descendant,
                'depth' => $relation->depth
            ];
        }

        return $result;
    }

    /**
     * Получение TV параметров ресурса
     */
    private function getResourceTVs(int $resourceId): array
    {
        $tvValues = [];

        $tvRecords = SiteTmplvarContentvalue::where('contentid', $resourceId)
            ->join('site_tmplvars', 'site_tmplvars.id', '=', 'site_tmplvar_contentvalues.tmplvarid')
            ->get(['site_tmplvars.name', 'site_tmplvars.type', 'site_tmplvar_contentvalues.value']);

        foreach ($tvRecords as $record) {
            $value = $record->value;

            if ($this->isMultiTV($record->type) && $this->isJson($value)) {
                $value = json_decode($value, true);
            }

            $tvValues[$record->name] = $value;
        }

        return $tvValues;
    }

    /**
     * Экспорт шаблонов
     */
    private function exportTemplates(): array
    {
        $this->info('Экспортируем шаблоны...');

        $templates = SiteTemplate::orderBy('templatename')->get();
        $exportData = [];

        foreach ($templates as $template) {
            $exportData[] = [
                'id' => $template->id,
                'templatename' => $template->templatename,
                'description' => $template->description,
                'content' => $template->content,
                'category' => $template->category,
                'locked' => (bool)$template->locked,
                'selectable' => (bool)$template->selectable
            ];
        }

        $this->statistics['templates'] = count($exportData);
        return $exportData;
    }

    /**
     * Экспорт TV параметров
     */
    private function exportTVs(): array
    {
        $this->info('Экспортируем TV параметры...');

        $tvs = SiteTmplvar::orderBy('name')->get();
        $exportData = [];

        foreach ($tvs as $tv) {
            $exportData[] = [
                'id' => $tv->id,
                'name' => $tv->name,
                'caption' => $tv->caption,
                'description' => $tv->description,
                'type' => $tv->type,
                'category' => $tv->category,
                'elements' => $tv->elements,
                'default_text' => $tv->default_text,
                'rank' => $tv->rank,
                'display' => $tv->display,
                'display_params' => $tv->display_params,
                'locked' => (bool)$tv->locked,
                'templates' => $this->getTVTemplates($tv->id)
            ];
        }

        $this->statistics['tv'] = count($exportData);
        return $exportData;
    }

    /**
     * Получение шаблонов, к которым привязан TV
     */
    private function getTVTemplates(int $tvId): array
    {
        return SiteTmplvarTemplate::where('tmplvarid', $tvId)
            ->pluck('templateid')
            ->toArray();
    }

    /**
     * Экспорт чанков
     */
    private function exportChunks(): array
    {
        $this->info('Экспортируем чанки...');

        $chunks = SiteHtmlsnippet::orderBy('name')->get();
        $exportData = [];

        foreach ($chunks as $chunk) {
            $exportData[] = [
                'id' => $chunk->id,
                'name' => $chunk->name,
                'description' => $chunk->description,
                'snippet' => $chunk->snippet,
                'category' => $chunk->category,
                'locked' => (bool)$chunk->locked,
                'disabled' => (bool)$chunk->disabled,
                'cache_type' => (bool)$chunk->cache_type
            ];
        }

        $this->statistics['chunks'] = count($exportData);
        return $exportData;
    }

    /**
     * Экспорт сниппетов
     */
    private function exportSnippets(): array
    {
        $this->info('Экспортируем сниппеты...');

        $snippets = SiteSnippet::orderBy('name')->get();
        $exportData = [];

        foreach ($snippets as $snippet) {
            $exportData[] = [
                'id' => $snippet->id,
                'name' => $snippet->name,
                'description' => $snippet->description,
                'snippet' => $snippet->snippet,
                'category' => $snippet->category,
                'locked' => (bool)$snippet->locked,
                'disabled' => (bool)$snippet->disabled,
                'cache_type' => (bool)$snippet->cache_type,
                'properties' => $snippet->properties,
                'moduleguid' => $snippet->moduleguid
            ];
        }

        $this->statistics['snippets'] = count($exportData);
        return $exportData;
    }

    /**
     * Экспорт плагинов
     */
    private function exportPlugins(): array
    {
        $this->info('Экспортируем плагины...');

        $plugins = SitePlugin::orderBy('name')->get();
        $exportData = [];

        foreach ($plugins as $plugin) {
            $exportData[] = [
                'id' => $plugin->id,
                'name' => $plugin->name,
                'description' => $plugin->description,
                'plugincode' => $plugin->plugincode,
                'category' => $plugin->category,
                'locked' => (bool)$plugin->locked,
                'disabled' => (bool)$plugin->disabled,
                'cache_type' => (bool)$plugin->cache_type,
                'properties' => $plugin->properties,
                'moduleguid' => $plugin->moduleguid,
                'events' => $this->getPluginEvents($plugin->id)
            ];
        }

        $this->statistics['plugins'] = count($exportData);
        return $exportData;
    }

    /**
     * Получение событий плагина
     */
    private function getPluginEvents(int $pluginId): array
    {
        $events = DB::table('site_plugin_events')
            ->where('pluginid', $pluginId)
            ->join('system_eventnames', 'system_eventnames.id', '=', 'site_plugin_events.evtid')
            ->select('system_eventnames.id', 'system_eventnames.name', 'site_plugin_events.priority')
            ->orderBy('site_plugin_events.priority')
            ->get();

        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'evtid' => $event->id,
                'name' => $event->name,
                'priority' => $event->priority
            ];
        }

        return $result;
    }

    /**
     * Экспорт модулей
     */
    private function exportModules(): array
    {
        $this->info('Экспортируем модули...');

        $modules = SiteModule::orderBy('name')->get();
        $exportData = [];

        foreach ($modules as $module) {
            $exportData[] = [
                'id' => $module->id,
                'name' => $module->name,
                'description' => $module->description,
                'modulecode' => $module->modulecode,
                'category' => $module->category,
                'locked' => (bool)$module->locked,
                'disabled' => (bool)$module->disabled,
                'wrap' => (bool)$module->wrap,
                'icon' => $module->icon,
                'guid' => $module->guid,
                'properties' => $module->properties,
                'enable_resource' => (bool)$module->enable_resource,
                'resourcefile' => $module->resourcefile,
                'enable_sharedparams' => (bool)$module->enable_sharedparams,
                'access' => $this->getModuleAccess($module->id),
                'dependencies' => $this->getModuleDependencies($module->id)
            ];
        }

        $this->statistics['modules'] = count($exportData);
        return $exportData;
    }

    /**
     * Получение прав доступа к модулю
     */
    private function getModuleAccess(int $moduleId): array
    {
        return SiteModuleAccess::where('module', $moduleId)
            ->pluck('usergroup')
            ->toArray();
    }

    /**
     * Получение зависимостей модуля
     */
    private function getModuleDependencies(int $moduleId): array
    {
        $deps = SiteModuleDepobj::where('module', $moduleId)->get();
        $result = [];

        foreach ($deps as $dep) {
            $result[] = [
                'resource' => $dep->resource,
                'type' => $dep->type
            ];
        }

        return $result;
    }

    /**
     * Экспорт данных Commerce
     */
    private function exportCommerce(): array
    {
        $this->info('Экспортируем данные Commerce...');

        $data = [];

        // Валюты
        if (Schema::hasTable('commerce_currency')) {
            $data['currency'] = DB::table('commerce_currency')->get()->toArray();
            $this->statistics['commerce_currency'] = count($data['currency']);
        }

        // Статусы заказов
        if (Schema::hasTable('commerce_order_statuses')) {
            $data['order_statuses'] = DB::table('commerce_order_statuses')->get()->toArray();
            $this->statistics['commerce_order_statuses'] = count($data['order_statuses']);
        }

        // Заказы
        if (Schema::hasTable('commerce_orders')) {
            $data['orders'] = DB::table('commerce_orders')->get()->toArray();
            $this->statistics['commerce_orders'] = count($data['orders']);
        }

        // Товары в заказах
        if (Schema::hasTable('commerce_order_products')) {
            $data['order_products'] = DB::table('commerce_order_products')->get()->toArray();
            $this->statistics['commerce_order_products'] = count($data['order_products']);
        }

        // История заказов
        if (Schema::hasTable('commerce_order_history')) {
            $data['order_history'] = DB::table('commerce_order_history')->get()->toArray();
            $this->statistics['commerce_order_history'] = count($data['order_history']);
        }

        // Платежи
        if (Schema::hasTable('commerce_order_payments')) {
            $data['order_payments'] = DB::table('commerce_order_payments')->get()->toArray();
            $this->statistics['commerce_order_payments'] = count($data['order_payments']);
        }

        return $data;
    }

    /**
     * Экспорт данных EvoSearch
     */
    private function exportEvoSearch(): array
    {
        $this->info('Экспортируем данные EvoSearch...');

        if (!Schema::hasTable('evosearch_table')) {
            return [];
        }

        $data = DB::table('evosearch_table')->get()->toArray();
        $this->statistics['evosearch'] = count($data);

        return $data;
    }

    /**
     * Экспорт данных ListTV
     */
    private function exportListTV(): array
    {
        $this->info('Экспортируем данные ListTV...');

        $data = [];

        // Категории ListTV
        if (Schema::hasTable('list_catagory_table')) {
            $data['categories'] = DB::table('list_catagory_table')->get()->toArray();
            $this->statistics['list_categories'] = count($data['categories']);
        }

        // Значения ListTV
        if (Schema::hasTable('list_value_table')) {
            $data['values'] = DB::table('list_value_table')->get()->toArray();
            $this->statistics['list_values'] = count($data['values']);
        }

        return $data;
    }

    /**
     * Проверка MultiTV
     */
    private function isMultiTV(string $type): bool
    {
        return in_array($type, ['multitv', 'custom_tv:multitv']);
    }

    /**
     * Проверка JSON
     */
    private function isJson(?string $string): bool
    {
        if (empty($string)) return false;
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Сохранение в файл
     * Добавляем связи Closure в отдельный блок
     */
    private function saveToFile(array $data, string $filename): string
    {
        // Добавляем связи Closure в данные, если они есть
        if (!empty($this->closureRelations)) {
            $data['closure_relations'] = $this->closureRelations;
        }

        $options = JSON_UNESCAPED_UNICODE;
        if ($this->option('pretty')) {
            $options |= JSON_PRETTY_PRINT;
        }

        $content = json_encode($data, $options);

        $exportPath = MODX_BASE_PATH . 'assets/export/';
        if (!is_dir($exportPath)) {
            mkdir($exportPath, 0755, true);
        }

        $fullPath = $exportPath . $filename;
        file_put_contents($fullPath, $content);

        return $fullPath;
    }

    /**
     * Подсчет ресурсов
     */
    private function countResources(array $structure): int
    {
        $count = 0;
        foreach ($structure as $item) {
            $count++;
            if (!empty($item['children'])) {
                $count += $this->countResources($item['children']);
            }
        }
        return $count;
    }

    /**
     * Отображение статистики
     */
    private function displayStatistics(): void
    {
        $this->info('Статистика экспорта:');

        $rows = [
            ['Ресурсы', $this->statistics['resources']],
            ['Шаблоны', $this->statistics['templates']],
            ['TV параметры', $this->statistics['tv']],
            ['Значения TV', $this->statistics['tv_values']],
            ['Чанки', $this->statistics['chunks']],
            ['Сниппеты', $this->statistics['snippets']],
            ['Плагины', $this->statistics['plugins']],
            ['Модули', $this->statistics['modules']],
            ['Категории', $this->statistics['categories']],
            ['Связи Closure', $this->statistics['closure_relations']],
        ];

        // Добавляем Commerce статистику если есть
        if ($this->statistics['commerce_currency'] > 0) {
            $rows[] = ['Commerce валюты', $this->statistics['commerce_currency']];
        }
        if ($this->statistics['commerce_order_statuses'] > 0) {
            $rows[] = ['Commerce статусы', $this->statistics['commerce_order_statuses']];
        }
        if ($this->statistics['commerce_orders'] > 0) {
            $rows[] = ['Commerce заказы', $this->statistics['commerce_orders']];
        }
        if ($this->statistics['commerce_order_products'] > 0) {
            $rows[] = ['Commerce товары', $this->statistics['commerce_order_products']];
        }

        // Добавляем EvoSearch статистику
        if ($this->statistics['evosearch'] > 0) {
            $rows[] = ['EvoSearch записи', $this->statistics['evosearch']];
        }

        // Добавляем ListTV статистику
        if ($this->statistics['list_categories'] > 0) {
            $rows[] = ['ListTV категории', $this->statistics['list_categories']];
        }
        if ($this->statistics['list_values'] > 0) {
            $rows[] = ['ListTV значения', $this->statistics['list_values']];
        }

        $this->table(['Элемент', 'Количество'], $rows);
    }
}
