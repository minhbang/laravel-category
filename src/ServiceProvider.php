<?php

namespace Minhbang\Category;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'category');
        $this->loadViewsFrom(__DIR__ . '/../views', 'category');
        $this->publishes(
            [
                __DIR__ . '/../views'                           => base_path('resources/views/vendor/category'),
                __DIR__ . '/../lang'                            => base_path('resources/lang/vendor/category'),
                __DIR__ . '/../config/category.php'             => config_path('category.php'),
                __DIR__ . '/../database/migrations/' .
                '2015_09_16_155451_create_categories_table.php' =>
                    database_path('migrations/' . '2015_09_16_155451_create_categories_table.php'),
            ]
        );

        if (config('category.add_route') && !$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }
        // pattern filters
        $router->pattern('category', '[0-9]+');
        // model bindings
        $router->model('category', 'Minhbang\Category\Item');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/category.php', 'category');
        $this->app['category'] = $this->app->share(
            function () {
                return new Category(
                    config('category.types'),
                    config('category.max_depth')
                );
            }
        );
        // add Category alias
        $this->app->booting(
            function () {
                AliasLoader::getInstance()->alias('Category', Facade::class);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['category'];
    }
}