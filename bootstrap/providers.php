<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TableServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Dashboard\Providers\DashboardServiceProvider;
use Telegram\Bot\Laravel\TelegramServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
    AppServiceProvider::class,
    DataTablesServiceProvider::class,
    RouteServiceProvider::class,
    TableServiceProvider::class,
    AuthServiceProvider::class,
    DashboardServiceProvider::class,
    TelegramServiceProvider::class,
];
