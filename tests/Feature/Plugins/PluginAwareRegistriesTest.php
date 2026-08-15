<?php

namespace Tests\Feature\Plugins;

use App\Plugins\PluginManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Modules\Catalog\Admin\Tables\CategoryTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;
use Tests\TestCase;

class PluginAwareRegistriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_optional_registrations_follow_effective_plugin_state_at_runtime(): void
    {
        $plugins = app(PluginManager::class);
        $menus = app(MenuRegistry::class);
        $tables = app(TableRegistry::class);

        $this->assertArrayNotHasKey('catalog', $menus->all());
        $this->assertArrayNotHasKey('categories', $tables->all());

        $plugins->replaceEnabledPackages(['figure-admin/catalog']);

        $this->assertArrayHasKey('catalog', $menus->all());
        $this->assertSame(CategoryTable::class, $tables->resolve('categories'));

        $plugins->replaceEnabledPackages([]);

        $this->assertArrayNotHasKey('catalog', $menus->all());
        $this->expectException(InvalidArgumentException::class);
        $tables->resolve('categories');
    }

    public function test_disabled_table_endpoint_is_indistinguishable_from_an_unknown_table(): void
    {
        $this->withoutMiddleware();

        foreach (['services', 'not-registered'] as $table) {
            $this->postJson("/admin/get-data/{$table}")
                ->assertNotFound()
                ->assertExactJson([
                    'error' => true,
                    'data' => null,
                    'message' => 'The requested table is not available.',
                ]);
        }
    }

    public function test_enabling_all_optional_plugins_exposes_their_registered_menus(): void
    {
        $packages = [
            'figure-admin/catalog',
            'figure-admin/workforce',
            'figure-admin/booking',
            'figure-admin/payments',
            'figure-admin/promotions',
            'figure-admin/communications',
            'figure-admin/cms',
        ];

        app(PluginManager::class)->replaceEnabledPackages($packages);

        $menuKeys = [
            'payments',
            'booking',
            'catalog',
            'promotions',
            'workforce',
            'cms',
            'communications',
        ];

        $this->assertSame(
            $menuKeys,
            array_values(array_intersect(array_keys(app(MenuRegistry::class)->all()), $menuKeys)),
        );
    }
}
