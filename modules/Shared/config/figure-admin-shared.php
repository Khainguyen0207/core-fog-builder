<?php

return [
    'routes' => [
        'enabled' => true,
        'prefix' => 'admin',
        'name_prefix' => 'admin.',
        'middleware' => ['web', 'auth'],
    ],

    'table' => [
        'previous_url_session_prefix' => 'previous_table_url',
    ],

    'branding' => [
        'name' => env('APP_NAME', 'Figure Admin'),
        'title_suffix' => 'Admin',
        'home_url' => '/',
        'logo' => 'assets/img/favicon/logo.png',
        'favicon' => 'assets/img/favicon/favicon.png',
        'description' => '',
        'keywords' => '',
        'canonical_url' => '',
        'creator_name' => 'FogDeveloper',
        'creator_url' => 'https://github.com/Khainguyen0207/',
    ],

    'layout' => [
        'assets_path' => '/assets',
    ],

    'assets' => [
        'vite' => [
            'modules/Shared/resources/js/app.js',
            'modules/Shared/resources/scss/admin.scss',
        ],
        'scripts' => ['https://buttons.github.io/buttons.js'],
    ],

    'logout' => [
        'route' => 'admin.logout',
        'url' => '/admin/logout',
    ],

    'menu' => [
        'log_viewer_route' => null,
    ],

    'user' => [
        'avatar_path' => 'assets/img/avatars',
        'default_avatar' => 'default-avatar.png',
    ],
];
