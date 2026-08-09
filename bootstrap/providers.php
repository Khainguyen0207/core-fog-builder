<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TableServiceProvider;
use Modules\AdminUi\Providers\AdminUiServiceProvider;
use Modules\Catalog\Providers\CatalogServiceProvider;
use Modules\Users\Providers\UsersServiceProvider;
use Telegram\Bot\Laravel\TelegramServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
    AppServiceProvider::class,
    DataTablesServiceProvider::class,
    RouteServiceProvider::class,
    TableServiceProvider::class,
    AdminUiServiceProvider::class,
    CatalogServiceProvider::class,
    UsersServiceProvider::class,
    TelegramServiceProvider::class,
];
