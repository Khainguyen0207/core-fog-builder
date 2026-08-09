<?php

namespace Tests\Feature\Modules\Workforce;

use App\Table\Configs\TableConfig;
use Illuminate\Support\Facades\Route;
use Modules\Workforce\Admin\Tables\StaffReviewTable;
use Modules\Workforce\Admin\Tables\StaffTable;
use Modules\Workforce\Http\Admin\Controllers\StaffController;
use Modules\Workforce\Http\Admin\Controllers\StaffReviewController;
use Modules\Workforce\Http\Admin\Controllers\StaffSettingController;
use Tests\TestCase;

class WorkforceAdminModuleTest extends TestCase
{
    public function test_workforce_routes_are_registered_to_module_controllers(): void
    {
        $routes = [
            'admin.staffs.index' => [StaffController::class.'@index', 'admin/staffs', 'GET'],
            'admin.staffs.create' => [StaffController::class.'@create', 'admin/staffs/create', 'GET'],
            'admin.staffs.store' => [StaffController::class.'@store', 'admin/staffs', 'POST'],
            'admin.staffs.show' => [StaffController::class.'@show', 'admin/staffs/{staff}', 'GET'],
            'admin.staffs.edit' => [StaffController::class.'@edit', 'admin/staffs/{staff}/edit', 'GET'],
            'admin.staffs.update' => [StaffController::class.'@update', 'admin/staffs/{staff}', 'PUT'],
            'admin.staffs.destroy' => [StaffController::class.'@destroy', 'admin/staffs/{staff}', 'DELETE'],
            'admin.staff-reviews.index' => [StaffReviewController::class.'@index', 'admin/staff-reviews', 'GET'],
            'admin.staff-reviews.show' => [StaffReviewController::class.'@show', 'admin/staff-reviews/{staffReview}', 'GET'],
            'admin.settings.active-staff.index' => [StaffSettingController::class.'@activeStaff', 'admin/settings/max-active-staff', 'GET'],
            'admin.settings.active-staff.update' => [StaffSettingController::class.'@updateActiveStaff', 'admin/settings/max-active-staff', 'PUT'],
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

    public function test_workforce_tables_and_views_are_registered(): void
    {
        $tables = app(TableConfig::class);

        $this->assertSame(StaffTable::class, $tables->resolve('staffs'));
        $this->assertSame(StaffReviewTable::class, $tables->resolve('staff-reviews'));
        $this->assertTrue(view()->exists('workforce::forms.base'));
        $this->assertTrue(view()->exists('workforce::forms.staff-review.details'));
        $this->assertTrue(view()->exists('workforce::tables.index'));
    }
}
