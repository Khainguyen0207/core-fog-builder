<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Admin\Controllers\BookingController;
use Modules\Booking\Http\Admin\Controllers\BookingServiceController;
use Modules\Booking\Http\Admin\Controllers\CalendarController;

Route::resource('admin/bookings', BookingController::class)
    ->middleware(['web', 'plugin:figure-admin/booking', 'auth', 'ip.manager'])
    ->names('admin.bookings');

Route::post('admin/bookings/export', [BookingController::class, 'export'])
    ->middleware(['web', 'plugin:figure-admin/booking', 'auth', 'ip.manager'])
    ->name('admin.bookings.export');

Route::resource('admin/booking-services', BookingServiceController::class)
    ->middleware(['web', 'plugin:figure-admin/booking', 'auth', 'ip.manager'])
    ->names('admin.booking-services');

Route::get('admin/calendar', [CalendarController::class, 'index'])
    ->middleware(['web', 'plugin:figure-admin/booking', 'auth', 'ip.manager'])
    ->name('admin.calendar.index');

Route::get('admin/calendar/events', [CalendarController::class, 'events'])
    ->middleware(['web', 'plugin:figure-admin/booking', 'auth', 'ip.manager'])
    ->name('admin.calendar.events');
