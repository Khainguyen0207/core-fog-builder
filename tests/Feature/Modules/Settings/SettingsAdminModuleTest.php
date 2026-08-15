<?php

namespace Tests\Feature\Modules\Settings;

use Illuminate\Support\Facades\Route;
use Modules\Settings\Admin\Tables\SettingTable;
use Modules\Settings\Http\Admin\Controllers\SettingController;
use Modules\Shared\Tables\Registry\TableRegistry;
use Modules\Workforce\Http\Admin\Controllers\StaffSettingController;
use Tests\TestCase;

class SettingsAdminModuleTest extends TestCase
{
    public function test_settings_routes_are_registered_to_module_controller(): void
    {
        $routes = [
            'admin.settings.index' => [SettingController::class.'@index', 'admin/settings', 'GET'],
            'admin.settings.store' => [SettingController::class.'@update', 'admin/settings', 'POST'],
            'admin.settings.sepay' => [SettingController::class.'@sePay', 'admin/settings/sepay', 'GET'],
            'admin.settings.work-time' => [SettingController::class.'@workTime', 'admin/settings/work-time', 'GET'],
            'admin.settings.information-system' => [SettingController::class.'@informationSystem', 'admin/settings/information-system', 'GET'],
            'admin.settings.telegram' => [SettingController::class.'@telegram', 'admin/settings/telegram', 'GET'],
            'admin.settings.plugins' => [SettingController::class.'@plugins', 'admin/settings/plugins', 'GET'],
            'admin.settings.plugins.update' => [SettingController::class.'@updatePlugins', 'admin/settings/plugins', 'POST'],
        ];

        foreach ($routes as $name => [$controller, $uri, $method]) {
            $route = Route::getRoutes()->getByName($name);

            $this->assertNotNull($route);
            $this->assertSame($controller, $route->getActionName());
            $this->assertSame($uri, $route->uri());
            $this->assertContains($method, $route->methods());
            $expectedMiddleware = ['web', 'auth', 'ip.manager'];

            if ($name === 'admin.settings.sepay') {
                $expectedMiddleware[] = 'plugin:figure-admin/payments';
            }

            if ($name === 'admin.settings.telegram') {
                $expectedMiddleware[] = 'plugin:figure-admin/communications';
            }

            $this->assertSame($expectedMiddleware, $route->gatherMiddleware());
        }
    }

    public function test_setting_table_and_views_are_registered(): void
    {
        $this->assertSame(SettingTable::class, app(TableRegistry::class)->resolve('base table'));
        $this->assertTrue(view()->exists('settings::forms.base'));
        $this->assertTrue(view()->exists('shared::tables.page'));
        $this->assertTrue(view()->exists('settings::admin.pages.settings.card'));
        $this->assertTrue(view()->exists('settings::admin.pages.settings.plugins'));
    }

    public function test_workforce_active_staff_routes_remain_registered_separately(): void
    {
        $route = Route::getRoutes()->getByName('admin.settings.active-staff.index');

        $this->assertNotNull($route);
        $this->assertSame(StaffSettingController::class.'@activeStaff', $route->getActionName());
        $this->assertSame('admin/settings/max-active-staff', $route->uri());
        $this->assertSame(
            ['web', 'plugin:figure-admin/workforce', 'auth', 'ip.manager'],
            $route->gatherMiddleware(),
        );
    }
}
