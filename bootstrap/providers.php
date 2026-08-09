<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use Telegram\Bot\Laravel\TelegramServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
    AppServiceProvider::class,
    DataTablesServiceProvider::class,
    RouteServiceProvider::class,
    TelegramServiceProvider::class,
];
