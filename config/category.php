<?php
return [
    'types'        => ['article', 'product'],
    'default_type' => 'article',
    'presenter'    => Minhbang\Category\ItemPresenter::class,
    'add_route'    => true,
    'max_depth'    => 5,
    'middlewares'  => 'admin',
];