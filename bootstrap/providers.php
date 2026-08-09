<?php

use App\Providers\RouteServiceProvider;
use App\Providers\TableServiceProvider;
use Modules\AdminUi\Providers\AdminUiServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    Yajra\DataTables\DataTablesServiceProvider::class,
    RouteServiceProvider::class,
    TableServiceProvider::class,
    AdminUiServiceProvider::class,
    Telegram\Bot\Laravel\TelegramServiceProvider::class,
];
