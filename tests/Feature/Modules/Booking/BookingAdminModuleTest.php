<?php

namespace Tests\Feature\Modules\Booking;

use App\Plugins\PluginManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Modules\Booking\Admin\Tables\BookingServiceTable;
use Modules\Booking\Admin\Tables\BookingTable;
use Modules\Booking\Http\Admin\Controllers\BookingController;
use Modules\Booking\Http\Admin\Controllers\BookingServiceController;
use Modules\Booking\Http\Admin\Controllers\CalendarController;
use Modules\Shared\Tables\Registry\TableRegistry;
use Tests\TestCase;

class BookingAdminModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PluginManager::class)->replaceEnabledPackages([
            'figure-admin/catalog',
            'figure-admin/workforce',
            'figure-admin/booking',
        ]);
    }

    public function test_booking_routes_are_registered_to_module_controllers(): void
    {
        $routes = [
            'admin.bookings.index' => [BookingController::class.'@index', 'admin/bookings', 'GET'],
            'admin.bookings.create' => [BookingController::class.'@create', 'admin/bookings/create', 'GET'],
            'admin.bookings.store' => [BookingController::class.'@store', 'admin/bookings', 'POST'],
            'admin.bookings.show' => [BookingController::class.'@show', 'admin/bookings/{booking}', 'GET'],
            'admin.bookings.edit' => [BookingController::class.'@edit', 'admin/bookings/{booking}/edit', 'GET'],
            'admin.bookings.update' => [BookingController::class.'@update', 'admin/bookings/{booking}', 'PUT'],
            'admin.bookings.destroy' => [BookingController::class.'@destroy', 'admin/bookings/{booking}', 'DELETE'],
            'admin.bookings.export' => [BookingController::class.'@export', 'admin/bookings/export', 'POST'],
            'admin.booking-services.index' => [BookingServiceController::class.'@index', 'admin/booking-services', 'GET'],
            'admin.booking-services.create' => [BookingServiceController::class.'@create', 'admin/booking-services/create', 'GET'],
            'admin.booking-services.store' => [BookingServiceController::class.'@store', 'admin/booking-services', 'POST'],
            'admin.booking-services.show' => [BookingServiceController::class.'@show', 'admin/booking-services/{booking_service}', 'GET'],
            'admin.booking-services.edit' => [BookingServiceController::class.'@edit', 'admin/booking-services/{booking_service}/edit', 'GET'],
            'admin.booking-services.update' => [BookingServiceController::class.'@update', 'admin/booking-services/{booking_service}', 'PUT'],
            'admin.booking-services.destroy' => [BookingServiceController::class.'@destroy', 'admin/booking-services/{booking_service}', 'DELETE'],
            'admin.calendar.index' => [CalendarController::class.'@index', 'admin/calendar', 'GET'],
            'admin.calendar.events' => [CalendarController::class.'@events', 'admin/calendar/events', 'GET'],
        ];

        foreach ($routes as $name => [$controller, $uri, $method]) {
            $route = Route::getRoutes()->getByName($name);

            $this->assertNotNull($route);
            $this->assertSame($controller, $route->getActionName());
            $this->assertSame($uri, $route->uri());
            $this->assertContains($method, $route->methods());
            $this->assertSame(['web', 'plugin:figure-admin/booking', 'auth', 'ip.manager'], $route->gatherMiddleware());
        }

        $routeNames = array_map(
            fn ($route) => $route->getName(),
            Route::getRoutes()->getRoutes()
        );

        $this->assertLessThan(
            array_search('admin.bookings.export', $routeNames, true),
            array_search('admin.bookings.store', $routeNames, true)
        );
        $this->assertLessThan(
            array_search('admin.booking-services.index', $routeNames, true),
            array_search('admin.bookings.export', $routeNames, true)
        );
        $this->assertLessThan(
            array_search('admin.calendar.events', $routeNames, true),
            array_search('admin.calendar.index', $routeNames, true)
        );
    }

    public function test_booking_tables_and_views_are_registered(): void
    {
        $tables = app(TableRegistry::class);

        $this->assertSame(BookingTable::class, $tables->resolve('bookings'));
        $this->assertSame(BookingServiceTable::class, $tables->resolve('booking-services'));
        $this->assertTrue(view()->exists('booking::forms.base'));
        $this->assertTrue(view()->exists('shared::tables.page'));
        $this->assertTrue(view()->exists('booking::admin.forms.booking.details'));
        $this->assertTrue(view()->exists('booking::admin.templates.invoice'));
        $this->assertTrue(view()->exists('booking::admin.pages.calendar.index'));
    }
}
