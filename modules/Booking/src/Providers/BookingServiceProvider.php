<?php

namespace Modules\Booking\Providers;

use App\Events\CustomerCreatedEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Booking\Admin\Tables\BookingServiceTable;
use Modules\Booking\Admin\Tables\BookingTable;
use Modules\Booking\Listeners\AttachHistoricalBookings;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;

class BookingServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'booking');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');
        Event::listen(CustomerCreatedEvent::class, AttachHistoricalBookings::class);

        $tables->register('bookings', BookingTable::class, 'figure-admin/booking');
        $tables->register('booking-services', BookingServiceTable::class, 'figure-admin/booking');
        $menus->register('booking', [
            'name' => 'Booking', 'icon' => 'menu-icon tf-icons bx bx-calendar-check',
            'route' => 'admin.calendar.index', 'active' => ['admin.calendar.*', 'admin.bookings.*'],
            'children' => [
                ['name' => 'Calendar', 'icon' => 'menu-icon tf-icons bx bx-calendar me-2', 'route' => 'admin.calendar.index', 'active' => ['admin.calendar.*']],
                ['name' => 'Bookings', 'icon' => 'menu-icon tf-icons bx bx-calendar-check me-2', 'route' => 'admin.bookings.index', 'active' => ['admin.bookings.*']],
            ],
        ], 500, 'figure-admin/booking');
    }
}
