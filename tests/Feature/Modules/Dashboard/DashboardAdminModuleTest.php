<?php

namespace Tests\Feature\Modules\Dashboard;

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
}
