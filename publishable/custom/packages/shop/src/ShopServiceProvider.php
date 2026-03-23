<?php

namespace EvolutionCMS\Shop;

use EvolutionCMS\ServiceProvider;
use EvolutionCMS\Shop\Services\eFilterService;
use EvolutionCMS\Shop\Interfaces\FilterServiceInterface;
use EvolutionCMS\Shop\Services\RunSnippetService;
use EvolutionCMS\Shop\Interfaces\RunSnippetServiceInterface;

class ShopServiceProvider extends ServiceProvider
{
    protected $namespace = 'shop';
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadPluginsFrom(
            dirname(__DIR__) . '/plugins/'
        );
        $this->app->singleton(FilterServiceInterface::class, function ($app) {
            return new eFilterService($app);
        });
        $this->app->singleton(RunSnippetServiceInterface::class, function ($app) {
            return new RunSnippetService($app);
        });
    }

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerBladeDirectives();
    }

    protected function registerBladeDirectives()
    {
        $directivesPath = __DIR__ . '/BladeDirectives';

        if (is_dir($directivesPath)) {
            $files = scandir($directivesPath);

            foreach ($files as $file) {
                $className = pathinfo($file, PATHINFO_FILENAME);
                $fullClassName = "EvolutionCMS\\Shop\\BladeDirectives\\{$className}";

                if (class_exists($fullClassName) && method_exists($fullClassName, 'register')) {
                    call_user_func([$fullClassName, 'register']);
                }
            }
        }
    }
}
