<?php

namespace Modules\Booking\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Booking\Admin\Tables\BookingServiceTable;
use Modules\Booking\Admin\Tables\BookingTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;

class BookingServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'booking');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('bookings', BookingTable::class);
        $tables->register('booking-services', BookingServiceTable::class);
        $menus->register('booking', [
            'name' => 'Booking', 'icon' => 'menu-icon tf-icons bx bx-calendar-check',
            'route' => 'admin.calendar.index', 'active' => ['admin.calendar.*', 'admin.bookings.*'],
            'children' => [
                ['name' => 'Calendar', 'icon' => 'menu-icon tf-icons bx bx-calendar me-2', 'route' => 'admin.calendar.index', 'active' => ['admin.calendar.*']],
                ['name' => 'Bookings', 'icon' => 'menu-icon tf-icons bx bx-calendar-check me-2', 'route' => 'admin.bookings.index', 'active' => ['admin.bookings.*']],
            ],
        ], 500);
    }
}
