<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TableServiceProvider;
use Modules\AdminUi\Providers\AdminUiServiceProvider;
use Modules\Catalog\Providers\CatalogServiceProvider;
use Modules\Customers\Providers\CustomersServiceProvider;
use Modules\Users\Providers\UsersServiceProvider;
use Modules\Workforce\Providers\WorkforceServiceProvider;
use Telegram\Bot\Laravel\TelegramServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
    AppServiceProvider::class,
    DataTablesServiceProvider::class,
    RouteServiceProvider::class,
    TableServiceProvider::class,
    AdminUiServiceProvider::class,
    CatalogServiceProvider::class,
    CustomersServiceProvider::class,
    UsersServiceProvider::class,
    WorkforceServiceProvider::class,
    TelegramServiceProvider::class,
];
