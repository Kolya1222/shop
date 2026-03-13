<?php

namespace EvolutionCMS\Shop;

use EvolutionCMS\ServiceProvider;
use EvolutionCMS\Shop\Services\Filter\EFilterService;
use EvolutionCMS\Shop\Interfaces\FilterServiceInterface;

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
            return new EFilterService($app);
        });
    }

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot() {}
}
