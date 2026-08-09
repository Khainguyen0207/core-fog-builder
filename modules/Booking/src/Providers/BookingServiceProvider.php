<?php

namespace Modules\Booking\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Booking\Admin\Tables\BookingServiceTable;
use Modules\Booking\Admin\Tables\BookingTable;

class BookingServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'booking');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('bookings', BookingTable::class);
        $tables->register('booking-services', BookingServiceTable::class);
    }
}
