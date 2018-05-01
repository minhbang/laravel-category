<?php
return [
    'presenter' => Minhbang\Category\CategoryPresenter::class,
    'max_depth' => 5,
    'middleware' => ['web', 'role:sys.admin'],
    'use_moderator' => true,
    // Định nghĩa menus cho category
    'menus' => [
        'backend.sidebar.content.category' => [
            'priority' => 2,
            'url' => 'route:backend.category.index',
            'label' => '__:Category',
            'icon' => 'fa-sitemap',
            'active' => 'backend/category*',
            'role' => 'sys.admin',
        ],
    ],
];