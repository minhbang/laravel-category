<?php
namespace Minhbang\LaravelCategory;

use Closure;
use Schema;

/**
 * Class CategoryMiddleware
 *
 * @package Minhbang\LaravelCategory
 */
class CategoryMiddleware
{
    public function handle($request, Closure $next)
    {
        // load default category type
        if (Schema::hasTable('categories')) {
            app('category')->switchType();
        }
        return $next($request);
    }
}