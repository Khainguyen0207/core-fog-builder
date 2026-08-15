<?php

namespace Tests\Feature\Modules\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Admin\Controllers\DashboardController;
use Tests\TestCase;

class DashboardAdminModuleTest extends TestCase
{
    public function test_dashboard_routes_are_registered_to_module_controller(): void
    {
        $routes = [
            'admin.dashboard.index' => [DashboardController::class.'@index', 'admin/dashboard', 'GET'],
            'admin.dashboard.create' => [DashboardController::class.'@create', 'admin/dashboard/create', 'GET'],
            'admin.dashboard.store' => [DashboardController::class.'@store', 'admin/dashboard', 'POST'],
            'admin.dashboard.show' => [DashboardController::class.'@show', 'admin/dashboard/{dashboard}', 'GET'],
            'admin.dashboard.edit' => [DashboardController::class.'@edit', 'admin/dashboard/{dashboard}/edit', 'GET'],
            'admin.dashboard.update' => [DashboardController::class.'@update', 'admin/dashboard/{dashboard}', 'PUT'],
            'admin.dashboard.destroy' => [DashboardController::class.'@destroy', 'admin/dashboard/{dashboard}', 'DELETE'],
        ];

        foreach ($routes as $name => [$controller, $uri, $method]) {
            $route = Route::getRoutes()->getByName($name);

            $this->assertNotNull($route);
            $this->assertSame($controller, $route->getActionName());
            $this->assertSame($uri, $route->uri());
            $this->assertContains($method, $route->methods());
            $this->assertSame(['web', 'auth', 'ip.manager'], $route->gatherMiddleware());
        }
    }

    public function test_dashboard_views_are_registered(): void
    {
        $this->assertTrue(view()->exists('dashboard::admin.pages.dashboard.index'));
        $this->assertTrue(view()->exists('dashboard::admin.pages.dashboard.components.kpi-row'));
        $this->assertTrue(view()->exists('dashboard::admin.pages.dashboard.components.revenue-services-row'));
        $this->assertTrue(view()->exists('dashboard::admin.pages.dashboard.components.activities-payments-row'));
    }

    public function test_mock_analytics_fixture_matches_dashboard_contract(): void
    {
        $path = base_path('modules/Dashboard/resources/data/analytic.json');
        $analytics = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame(1, $analytics['schema_version']);
        $this->assertLessThanOrEqual(
            Carbon::parse($analytics['weekEnd']),
            Carbon::parse($analytics['weekStart'])
        );

        foreach (['bookingThisWeek', 'pendingBookings', 'revenueThisWeek', 'expectedRevenueThisWeek'] as $key) {
            $this->assertIsNumeric($analytics['kpis'][$key]['value']);
            $this->assertIsNumeric($analytics['kpis'][$key]['growth']);
        }

        foreach ($analytics['charts'] as $chart) {
            $this->assertCount(7, $chart['labels']);

            foreach ($chart['series'] as $series) {
                $this->assertCount(count($chart['labels']), $series['data']);
                $this->assertContainsOnly('numeric', $series['data']);
            }
        }

        $this->assertCount(count($analytics['paymentStats']['labels']), $analytics['paymentStats']['series']);
        $this->assertCount(count($analytics['paymentStats']['labels']), $analytics['paymentStats']['icons']);
    }

    public function test_dashboard_controller_uses_mock_analytics_without_database_queries(): void
    {
        DB::enableQueryLog();

        $view = app(DashboardController::class)->index();

        $this->assertSame('dashboard::admin.pages.dashboard.index', $view->name());
        $this->assertSame(42, $view->getData()['kpis']['bookingThisWeek']['value']);
        $this->assertInstanceOf(Carbon::class, $view->getData()['weekStart']);
        $this->assertSame([], DB::getQueryLog());
    }
}
