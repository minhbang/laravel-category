<?php
Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\LaravelCategory'],
    function () {
        Route::group(
            ['prefix' => 'category', 'as' => 'backend.category.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'CategoryController@data']);
                Route::get('{category}/create', 'CategoryController@createChildOf');
                Route::post('move', ['as' => 'move', 'uses' => 'CategoryController@move']);
                Route::post('{category}', ['as' => 'storeChildOf', 'uses' => 'CategoryController@storeChildOf']);
            }
        );
        Route::get('category/of/{type}', ['as' => 'backend.category.type', 'uses' => 'CategoryController@index']);
        Route::resource('category', 'CategoryController');
    }
);