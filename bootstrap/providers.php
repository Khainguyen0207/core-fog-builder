<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TableServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Booking\Providers\BookingServiceProvider;
use Modules\Cms\Providers\CmsServiceProvider;
use Modules\Communications\Providers\CommunicationsServiceProvider;
use Modules\Dashboard\Providers\DashboardServiceProvider;
use Modules\Settings\Providers\SettingsServiceProvider;
use Telegram\Bot\Laravel\TelegramServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
    AppServiceProvider::class,
    DataTablesServiceProvider::class,
    RouteServiceProvider::class,
    TableServiceProvider::class,
    AuthServiceProvider::class,
    BookingServiceProvider::class,
    CmsServiceProvider::class,
    CommunicationsServiceProvider::class,
    DashboardServiceProvider::class,
    SettingsServiceProvider::class,
    TelegramServiceProvider::class,
];
