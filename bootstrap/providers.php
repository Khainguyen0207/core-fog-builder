<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TableServiceProvider;
use Modules\AdminUi\Providers\AdminUiServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Booking\Providers\BookingServiceProvider;
use Modules\Catalog\Providers\CatalogServiceProvider;
use Modules\Cms\Providers\CmsServiceProvider;
use Modules\Communications\Providers\CommunicationsServiceProvider;
use Modules\Customers\Providers\CustomersServiceProvider;
use Modules\Dashboard\Providers\DashboardServiceProvider;
use Modules\Payments\Providers\PaymentsServiceProvider;
use Modules\Promotions\Providers\PromotionsServiceProvider;
use Modules\Settings\Providers\SettingsServiceProvider;
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
    AuthServiceProvider::class,
    BookingServiceProvider::class,
    CatalogServiceProvider::class,
    CmsServiceProvider::class,
    CommunicationsServiceProvider::class,
    CustomersServiceProvider::class,
    DashboardServiceProvider::class,
    PaymentsServiceProvider::class,
    PromotionsServiceProvider::class,
    SettingsServiceProvider::class,
    UsersServiceProvider::class,
    WorkforceServiceProvider::class,
    TelegramServiceProvider::class,
];
