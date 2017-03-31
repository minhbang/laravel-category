<?php
Route::group(
    [
        'middleware' => config('category.middleware'),
        'prefix'     => 'backend',
        'as'         => 'backend.',
        'namespace'  => 'Minhbang\Category',
    ],
    function () {
        Route::group(
            ['prefix' => 'category', 'as' => 'category.'],
            function () {
                Route::get('of/{type}', ['as' => 'type', 'uses' => 'Controller@index']);
                Route::get('data', ['as' => 'data', 'uses' => 'Controller@data']);
                Route::get('{category}/create', ['as' => 'createChildOf', 'uses' => 'Controller@createChildOf']);
                Route::post('move', ['as' => 'move', 'uses' => 'Controller@move']);
                Route::post('{category}', ['as' => 'storeChildOf', 'uses' => 'Controller@storeChildOf']);
                Route::post('{category}/quick_update', ['as' => 'quick_update', 'uses' => 'Controller@quickUpdate']);
            }
        );

        Route::resource('category', 'Controller');
    }
);