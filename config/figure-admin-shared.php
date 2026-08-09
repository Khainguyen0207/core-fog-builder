<?php

return [
    'routes' => [
        'enabled' => true,
        'prefix' => 'admin',
        'name_prefix' => 'admin.',
        'middleware' => ['web', 'auth', 'ip.manager'],
    ],

    'menu' => [
        'log_viewer_route' => 'admin.log-viewer.index',
    ],
];
