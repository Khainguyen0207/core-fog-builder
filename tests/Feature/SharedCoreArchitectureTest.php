<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SharedCoreArchitectureTest extends TestCase
{
    private const FEATURE_MODULES = [
        'Auth',
        'Booking',
        'Catalog',
        'Cms',
        'Communications',
        'Customers',
        'Dashboard',
        'Payments',
        'Promotions',
        'Settings',
        'Users',
        'Workforce',
    ];

    public function test_legacy_shared_core_sources_and_caches_are_absent(): void
    {
        foreach (['app/Forms', 'app/Table', 'app/Panel', 'modules/AdminUi'] as $path) {
            $this->assertDirectoryDoesNotExist(base_path($path));
        }

        $this->assertFileDoesNotExist(app_path('Providers/TableServiceProvider.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/Admin/BulkDeleteController.php'));
        $this->assertFileDoesNotExist(base_path('bootstrap/cache/tables.php'));
        $this->assertFileDoesNotExist(base_path('bootstrap/cache/tables.meta.php'));

        foreach ([
            'App\\Forms\\BaseForm',
            'App\\Table\\BaseTable',
            'App\\Panel\\BasePanel',
            'Modules\\AdminUi\\Providers\\AdminUiServiceProvider',
        ] as $legacyClass) {
            $this->assertFalse(class_exists($legacyClass), $legacyClass);
        }
    }

    public function test_shared_is_host_independent_and_required_by_every_feature_module(): void
    {
        $sharedManifest = json_decode(
            File::get(base_path('modules/Shared/composer.json')),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        $this->assertSame('figure-admin/shared', $sharedManifest['name']);
        $this->assertArrayNotHasKey('figure-admin/host', $sharedManifest['require']);

        foreach (self::FEATURE_MODULES as $module) {
            $manifest = json_decode(
                File::get(base_path("modules/{$module}/composer.json")),
                true,
                512,
                JSON_THROW_ON_ERROR,
            );

            $this->assertSame('dev-main', $manifest['require']['figure-admin/shared'] ?? null, $module);
        }
    }

    public function test_bootstrap_has_no_table_scanner_and_shared_owns_active_ui_paths(): void
    {
        $providers = File::get(base_path('bootstrap/providers.php'));

        $this->assertStringNotContainsString('TableServiceProvider', $providers);
        $this->assertStringNotContainsString('Finder', $providers);
        $this->assertDirectoryDoesNotExist(resource_path('views/admin'));
        $this->assertFileDoesNotExist(resource_path('js/app.js'));
        $this->assertFileDoesNotExist(resource_path('scss/admin.scss'));

        $config = require base_path('modules/Shared/config/figure-admin-shared.php');

        $this->assertSame([
            'modules/Shared/resources/js/app.js',
            'modules/Shared/resources/scss/admin.scss',
        ], $config['assets']['vite']);
        $this->assertFileExists(base_path($config['assets']['vite'][0]));
        $this->assertFileExists(base_path($config['assets']['vite'][1]));
        $this->assertDirectoryExists(base_path('modules/Shared/resources/views/layouts'));
        $this->assertArrayHasKey('shared', app('view')->getFinder()->getHints());
    }

    public function test_host_preserves_the_application_middleware_contract(): void
    {
        $this->assertSame(
            ['web', 'auth', 'ip.manager'],
            config('figure-admin-shared.routes.middleware'),
        );
        $this->assertSame(
            'admin.log-viewer.index',
            config('figure-admin-shared.menu.log_viewer_route'),
        );
    }
}
