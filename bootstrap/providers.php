<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TableServiceProvider;
use Modules\AdminUi\Providers\AdminUiServiceProvider;
use Modules\Users\Providers\UsersServiceProvider;
use Telegram\Bot\Laravel\TelegramServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
    AppServiceProvider::class,
    DataTablesServiceProvider::class,
    RouteServiceProvider::class,
    TableServiceProvider::class,
    AdminUiServiceProvider::class,
    UsersServiceProvider::class,
    TelegramServiceProvider::class,
];
