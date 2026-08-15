<?php

namespace Tests\Feature\Plugins;

use App\Models\Setting;
use App\Plugins\DatabasePluginStateStore;
use App\Plugins\PluginManager;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabasePluginStateStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_optional_plugins_default_to_disabled_without_a_setting(): void
    {
        $this->assertFalse($this->app->make(PluginManager::class)->isEnabled('figure-admin/catalog'));
    }

    public function test_enabled_plugins_are_loaded_from_the_json_setting(): void
    {
        Setting::query()->create([
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => json_encode(['figure-admin/catalog', 'figure-admin/workforce'], JSON_THROW_ON_ERROR),
        ]);

        $manager = $this->app->make(PluginManager::class);

        $this->assertTrue($manager->isEnabled('figure-admin/catalog'));
        $this->assertTrue($manager->isEnabled('figure-admin/workforce'));
        $this->assertFalse($manager->isEnabled('figure-admin/booking'));
    }

    public function test_invalid_json_fails_closed(): void
    {
        Setting::query()->create([
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => 'not-json',
        ]);

        $this->assertFalse($this->app->make(PluginManager::class)->isEnabled('figure-admin/catalog'));
    }

    public function test_setting_seeder_adds_defaults_without_resetting_plugin_state(): void
    {
        Setting::query()->create([
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => '["figure-admin/cms"]',
            'description' => 'Old description',
        ]);
        Setting::query()->create([
            'key' => 'custom_setting',
            'value' => 'preserved',
        ]);

        $this->seed(SettingSeeder::class);

        $this->assertDatabaseHas('settings', [
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => '["figure-admin/cms"]',
            'description' => 'Enabled optional plugins',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'custom_setting',
            'value' => 'preserved',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'work_time_monday',
        ]);
    }

    public function test_enabled_plugins_are_not_exposed_by_public_settings_api(): void
    {
        Setting::query()->create([
            'key' => DatabasePluginStateStore::SETTING_KEY,
            'value' => '["figure-admin/cms"]',
        ]);

        $response = $this->getJson('/api/v1/system-settings');

        $response->assertOk();
        $response->assertJsonMissingPath('data.enabled_plugins');
    }
}
