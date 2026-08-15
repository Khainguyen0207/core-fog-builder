<?php

namespace Tests\Feature\Modules\Settings;

use App\Enums\UserGroupRoleEnum;
use App\Http\Middleware\IpManagerMiddleware;
use App\Models\Setting;
use App\Models\User;
use App\Plugins\DatabasePluginStateStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PluginSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(IpManagerMiddleware::class);
        $this->actingAs(User::query()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
            'group_role' => UserGroupRoleEnum::ADMIN,
            'is_active' => 1,
        ]));
    }

    public function test_plugins_page_lists_optional_composer_packages(): void
    {
        $this->get(route('admin.settings.plugins'))
            ->assertOk()
            ->assertSee('figure-admin/catalog')
            ->assertSee('figure-admin/booking')
            ->assertSee('figure-admin/cms')
            ->assertDontSee('figure-admin/shared');
    }

    public function test_plugin_selection_is_stored_as_json(): void
    {
        $this->post(route('admin.settings.plugins.update'), [
            'enabled_plugins' => ['figure-admin/catalog', 'figure-admin/workforce'],
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('settings', [
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => '["figure-admin/catalog","figure-admin/workforce"]',
        ]);
    }

    public function test_enabling_a_plugin_without_dependencies_is_rejected(): void
    {
        $this->post(route('admin.settings.plugins.update'), [
            'enabled_plugins' => ['figure-admin/workforce'],
        ])->assertSessionHasErrors('enabled_plugins');

        $this->assertDatabaseMissing('settings', [
            'key' => DatabasePluginStateStore::SETTING_KEY,
        ]);
    }

    public function test_disabling_a_dependency_while_keeping_its_dependent_is_rejected(): void
    {
        Setting::query()->create([
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => '["figure-admin/catalog","figure-admin/workforce"]',
        ]);

        $this->post(route('admin.settings.plugins.update'), [
            'enabled_plugins' => ['figure-admin/workforce'],
        ])->assertSessionHasErrors('enabled_plugins');

        $this->assertDatabaseHas('settings', [
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => '["figure-admin/catalog","figure-admin/workforce"]',
        ]);
    }
}
