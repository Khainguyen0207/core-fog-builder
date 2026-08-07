<?php

use App\Providers\RouteServiceProvider;
use App\Providers\TableServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    Yajra\DataTables\DataTablesServiceProvider::class,
    RouteServiceProvider::class,
    TableServiceProvider::class,
    Telegram\Bot\Laravel\TelegramServiceProvider::class,
];
