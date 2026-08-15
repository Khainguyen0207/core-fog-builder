<?php

namespace Tests\Feature\Plugins;

use App\Plugins\PluginManager;
use App\Plugins\PluginStatus;
use Tests\TestCase;

class PluginManagerRegistrationTest extends TestCase
{
    public function test_application_registers_the_declared_plugin_catalog(): void
    {
        $statuses = collect($this->app->make(PluginManager::class)->statuses())
            ->keyBy(fn (PluginStatus $status): string => $status->definition->packageName);

        $this->assertSame([
            'figure-admin/shared',
            'figure-admin/users',
            'figure-admin/auth',
            'figure-admin/customers',
            'figure-admin/dashboard',
            'figure-admin/settings',
            'figure-admin/catalog',
            'figure-admin/workforce',
            'figure-admin/booking',
            'figure-admin/payments',
            'figure-admin/promotions',
            'figure-admin/communications',
            'figure-admin/cms',
        ], $statuses->keys()->all());

        foreach ($statuses as $status) {
            $this->assertTrue($status->installed, $status->definition->packageName);
            $this->assertSame(
                $status->definition->core,
                $status->enabled,
                $status->definition->packageName,
            );
        }
    }
}
