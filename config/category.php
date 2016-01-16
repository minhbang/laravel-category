<?php
return [
    'types'        => ['article', 'ebook'],
    'default_type' => 'article',
    'presenter'    => Minhbang\Category\ItemPresenter::class,
    'add_route'    => true,
    'max_depth'    => 5,
    'middlewares'  => 'admin',
];