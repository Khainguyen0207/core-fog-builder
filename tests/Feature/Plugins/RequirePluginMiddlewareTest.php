<?php

namespace Tests\Feature\Plugins;

use App\Enums\UserGroupRoleEnum;
use App\Http\Middleware\IpManagerMiddleware;
use App\Http\Middleware\VerifyWebMiddleware;
use App\Models\Setting;
use App\Models\User;
use App\Plugins\Contracts\PluginPackageRepository;
use App\Plugins\DatabasePluginStateStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RequirePluginMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_disabled_plugin_admin_route_returns_not_found(): void
    {
        $this->withoutMiddleware(IpManagerMiddleware::class);
        $this->actingAs(User::factory()->create([
            'group_role' => UserGroupRoleEnum::ADMIN,
            'is_active' => true,
        ]));

        $response = $this->get('/admin/services');

        $response->assertNotFound();
    }

    public function test_disabled_plugin_api_route_uses_the_api_envelope(): void
    {
        $this->withoutMiddleware([VerifyWebMiddleware::class, IpManagerMiddleware::class]);

        $response = $this->getJson('/api/v1/services');

        $response->assertNotFound()->assertExactJson([
            'error' => true,
            'data' => null,
            'message' => 'The requested feature is not available.',
        ]);
    }

    public function test_enabled_plugin_route_continues_to_the_handler(): void
    {
        Setting::query()->create([
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => '["figure-admin/cms"]',
        ]);
        Route::middleware('plugin:figure-admin/cms')
            ->get('/plugin-gate-test', fn () => response('available'));

        $this->get('/plugin-gate-test')
            ->assertOk()
            ->assertSeeText('available');
    }

    public function test_requested_plugin_with_a_missing_package_returns_service_unavailable(): void
    {
        Setting::query()->create([
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => '["figure-admin/cms"]',
        ]);
        $this->app->bind(PluginPackageRepository::class, fn () => new class implements PluginPackageRepository
        {
            public function isInstalled(string $packageName): bool
            {
                return $packageName !== 'figure-admin/cms';
            }
        });
        Route::middleware('plugin:figure-admin/cms')
            ->get('/plugin-gate-test', fn () => response('available'));

        $this->get('/plugin-gate-test')
            ->assertServiceUnavailable();
    }

    public function test_optional_routes_remain_registered_while_disabled(): void
    {
        $route = Route::getRoutes()->getByName('admin.bookings.index');

        $this->assertNotNull($route);
        $this->assertContains('plugin:figure-admin/booking', $route->gatherMiddleware());
    }
}
