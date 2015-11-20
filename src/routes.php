<?php
Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\Category'],
    function () {
        Route::group(
            ['prefix' => 'category', 'as' => 'backend.category.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'Controller@data']);
                Route::get('{category}/create', 'Controller@createChildOf');
                Route::post('move', ['as' => 'move', 'uses' => 'Controller@move']);
                Route::post('{category}', ['as' => 'storeChildOf', 'uses' => 'Controller@storeChildOf']);
            }
        );
        Route::get('category/of/{type}', ['as' => 'backend.category.type', 'uses' => 'Controller@index']);
        Route::resource('category', 'Controller');
    }
);