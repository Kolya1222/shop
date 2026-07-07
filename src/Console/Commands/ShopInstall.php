<?php

namespace roilafx\Install\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Exception;

class ShopInstall extends Command
{
    protected $signature = 'shop:install 
                            {--force : Пропустить подтверждение}
                            {--no-clear : Не очищать кеш в конце}';
    protected $description = 'Автоматическая полная установка roilafx/shop на чистую Evolution CMS CE 3.1.30';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Будет выполнена полная установка магазина. Продолжить?')) {
            return 1;
        }

        $this->info('Начинаем установку магазина...');

        try {
            $this->step1_installPackage();
            $this->step2_dumpAutoload();
            $this->step3_runMigrations();
            $this->step4_publishAssets();
            $this->step5_importData();
            $this->step6_updateComposerAutoload();
            $this->step7_dumpAutoload();

            if (!$this->option('no-clear')) {
                $this->step8_clearCache();
            }

            $this->info('Магазин успешно установлен!');
        } catch (Exception $e) {
            $this->error('Ошибка установки: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    protected function step1_installPackage(): void
    {
        $this->info('[1/7] Установка пакета roilafx/shop через composer...');
        $process = new Process(['composer', 'require', 'roilafx/shop', '--no-interaction'], EVO_CORE_PATH);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            $this->getOutput()->write($buffer);
        });
        if (!$process->isSuccessful()) {
            throw new Exception('Ошибка composer require: ' . $process->getErrorOutput());
        }
    }

    protected function step2_dumpAutoload(): void
    {
        $this->info('[2/7] Обновление автозагрузки...');
        $this->runComposerDumpAutoload();
    }

    protected function step3_runMigrations(): void
    {
        $this->info('[3/7] Выполнение миграций...');
        $this->call('migrate');
    }

    protected function step4_publishAssets(): void
    {
        $this->info('[4/7] Публикация ресурсов...');
        $this->call('vendor:publish', [
            '--provider' => 'roilafx\\Install\\InstallServiceProvider',
            '--force' => true,
        ]);
    }

    protected function step5_importData(): void
    {
        $this->info('[5/7] Импорт структуры сайта...');
        $this->call('site:full-import', [
            '--all' => true,
            '--clear-first' => true,
        ]);
    }

    protected function step6_updateComposerAutoload(): void
    {
        $this->info('[6/7] Обновление автозагрузки в custom/composer.json...');
        $customComposerPath = EVO_CORE_PATH . 'custom/composer.json';
        if (!file_exists($customComposerPath)) {
            throw new Exception("Файл $customComposerPath не найден.");
        }

        $json = json_decode(file_get_contents($customComposerPath), true);
        if ($json === null) {
            throw new Exception("Некорректный JSON в $customComposerPath");
        }

        $psr4 = &$json['autoload']['psr-4'];
        if (!is_array($psr4)) {
            $psr4 = [];
        }
        $psr4['EvolutionCMS\\Shop\\'] = 'packages/shop/src/';
        $json['autoload']['psr-4'] = $psr4;

        file_put_contents($customComposerPath, json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        $this->line('   секция autoload.psr-4 обновлена.');
    }

    protected function step7_dumpAutoload(): void
    {
        $this->info('[7/7] Финальное обновление автозагрузки...');
        $this->runComposerDumpAutoload();
    }

    protected function step8_clearCache(): void
    {
        $this->info('Очистка кеша...');
        $this->call('cache:clear-full');
    }

    protected function runComposerDumpAutoload(): void
    {
        $process = new Process(['composer', 'dump-autoload'], EVO_CORE_PATH);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new Exception('Ошибка composer dump-autoload: ' . $process->getErrorOutput());
        }
        $this->line('   composer dump-autoload выполнен.');
    }
}