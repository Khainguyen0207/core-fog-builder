<?php

return [
    'plugins' => [
        'figure-admin/shared' => [
            'display_name' => 'Shared',
            'core' => true,
            'dependencies' => [],
        ],
        'figure-admin/users' => [
            'display_name' => 'Users',
            'core' => true,
            'dependencies' => ['figure-admin/shared'],
        ],
        'figure-admin/auth' => [
            'display_name' => 'Authentication',
            'core' => true,
            'dependencies' => ['figure-admin/shared', 'figure-admin/users'],
        ],
        'figure-admin/customers' => [
            'display_name' => 'Customers',
            'core' => true,
            'dependencies' => ['figure-admin/shared', 'figure-admin/users'],
        ],
        'figure-admin/dashboard' => [
            'display_name' => 'Dashboard',
            'core' => true,
            'dependencies' => ['figure-admin/shared'],
        ],
        'figure-admin/settings' => [
            'display_name' => 'Settings',
            'core' => true,
            'dependencies' => ['figure-admin/shared'],
        ],
        'figure-admin/catalog' => [
            'display_name' => 'Catalog',
            'core' => false,
            'dependencies' => [],
        ],
        'figure-admin/workforce' => [
            'display_name' => 'Workforce',
            'core' => false,
            'dependencies' => ['figure-admin/catalog'],
        ],
        'figure-admin/booking' => [
            'display_name' => 'Booking',
            'core' => false,
            'dependencies' => ['figure-admin/catalog', 'figure-admin/workforce'],
        ],
        'figure-admin/payments' => [
            'display_name' => 'Payments',
            'core' => false,
            'dependencies' => ['figure-admin/booking'],
        ],
        'figure-admin/promotions' => [
            'display_name' => 'Promotions',
            'core' => false,
            'dependencies' => ['figure-admin/booking'],
        ],
        'figure-admin/communications' => [
            'display_name' => 'Communications',
            'core' => false,
            'dependencies' => ['figure-admin/booking'],
        ],
        'figure-admin/cms' => [
            'display_name' => 'CMS',
            'core' => false,
            'dependencies' => [],
        ],
    ],
];
