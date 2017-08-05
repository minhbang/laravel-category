<?php

namespace Minhbang\Category;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use MenuManager;

/**
 * Class ServiceProvider
 *
 * @package Minhbang\Category
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'category');
        $this->loadViewsFrom(__DIR__.'/../views', 'category');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/../views' => base_path('resources/views/vendor/category'),
            __DIR__.'/../lang' => base_path('resources/lang/vendor/category'),
            __DIR__.'/../config/category.php' => config_path('category.php'),
        ]);

        // pattern filters
        $router->pattern('category', '[0-9]+');
        // model bindings
        $router->model('category', Category::class);
        MenuManager::addItems(config('category.menus'));
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/category.php', 'category');
        $this->app->singleton('category-manager', function () {
            return new Manager();
        });
        // add Category alias
        $this->app->booting(function () {
            AliasLoader::getInstance()->alias('CategoryManager', Facade::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['category-manager'];
    }
}
