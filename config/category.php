<?php
return [
    'factory'     => Minhbang\LaravelMenu\MenuFactory::class,
    'add_route'   => true,
    'max_depth'   => 5,
    'middlewares' => 'admin',
];