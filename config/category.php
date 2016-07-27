<?php
return [
    'types'         => ['article', 'ebook'],
    'default_type'  => 'article',
    'presenter'     => Minhbang\Category\CategoryPresenter::class,
    'add_route'     => true,
    'max_depth'     => 5,
    'middlewares'   => 'role:admin',
    'use_moderator' => true,
];