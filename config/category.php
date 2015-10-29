<?php
return [
    'default_type' => 'article',
    'factory'      => Minhbang\LaravelCategory\CategoryFactory::class,
    'presenter'    => Minhbang\LaravelCategory\CategoryItemRequest::class,
    'add_route'    => true,
    'max_depth'    => 5,
    'middlewares'  => 'admin',
];