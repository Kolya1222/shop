<?php

namespace roilafx\Install;

use EvolutionCMS\ServiceProvider;
use roilafx\Install\Console\Commands\ExportSiteStructure;
use roilafx\Install\Console\Commands\ImportSiteStructure;

class InstallServiceProvider extends ServiceProvider
{
    protected $namespace = 'install';
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            ExportSiteStructure::class,
            ImportSiteStructure::class,
        ]);
    }

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../publishable/views' => MODX_BASE_PATH . 'views/',
            __DIR__ . '/../publishable/resources' => MODX_BASE_PATH . 'resources/',
            __DIR__ . '/../publishable/custom' => EVO_CORE_PATH . 'custom/',
            __DIR__ . '/../publishable/assets' => MODX_BASE_PATH . 'assets/',
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }
}
