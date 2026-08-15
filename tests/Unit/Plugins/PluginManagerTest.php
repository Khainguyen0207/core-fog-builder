<?php

namespace Tests\Unit\Plugins;

use App\Plugins\Contracts\PluginPackageRepository;
use App\Plugins\Contracts\PluginStateStore;
use App\Plugins\PluginCatalog;
use App\Plugins\PluginManager;
use App\Plugins\PluginStatus;
use PHPUnit\Framework\TestCase;

class PluginManagerTest extends TestCase
{
    public function test_core_plugins_are_enabled_without_requested_state(): void
    {
        $manager = $this->manager([]);

        $this->assertTrue($manager->isEnabled('vendor/core'));
        $this->assertSame(PluginStatus::ENABLED, $manager->status('vendor/core')->reason);
    }

    public function test_optional_plugins_default_to_disabled(): void
    {
        $status = $this->manager([])->status('vendor/optional');

        $this->assertFalse($status->requested);
        $this->assertFalse($status->enabled);
        $this->assertSame(PluginStatus::DISABLED, $status->reason);
    }

    public function test_requested_plugin_requires_effective_dependencies(): void
    {
        $status = $this->manager(['vendor/dependent'])->status('vendor/dependent');

        $this->assertTrue($status->requested);
        $this->assertFalse($status->enabled);
        $this->assertSame(PluginStatus::DEPENDENCY_UNAVAILABLE, $status->reason);
        $this->assertSame(['vendor/optional'], $status->blockers);
    }

    public function test_requested_plugin_is_enabled_with_its_dependencies(): void
    {
        $manager = $this->manager(['vendor/optional', 'vendor/dependent']);

        $this->assertTrue($manager->isEnabled('vendor/optional'));
        $this->assertTrue($manager->isEnabled('vendor/dependent'));
    }

    public function test_missing_packages_fail_closed(): void
    {
        $status = $this->manager(['vendor/optional'], ['vendor/core', 'vendor/dependent'])
            ->status('vendor/optional');

        $this->assertFalse($status->enabled);
        $this->assertSame(PluginStatus::PACKAGE_MISSING, $status->reason);
    }

    public function test_unknown_requested_packages_are_ignored(): void
    {
        $manager = $this->manager(['vendor/unknown']);

        $this->assertFalse($manager->isEnabled('vendor/optional'));
    }

    /** @param list<string> $enabledPackages @param list<string> $installedPackages */
    private function manager(
        array $enabledPackages,
        array $installedPackages = ['vendor/core', 'vendor/optional', 'vendor/dependent'],
    ): PluginManager {
        $catalog = new PluginCatalog([
            'vendor/core' => [
                'display_name' => 'Core',
                'core' => true,
                'dependencies' => [],
            ],
            'vendor/optional' => [
                'display_name' => 'Optional',
                'core' => false,
                'dependencies' => [],
            ],
            'vendor/dependent' => [
                'display_name' => 'Dependent',
                'core' => false,
                'dependencies' => ['vendor/optional'],
            ],
        ]);

        $packages = new class($installedPackages) implements PluginPackageRepository
        {
            /** @param list<string> $installedPackages */
            public function __construct(private readonly array $installedPackages) {}

            public function isInstalled(string $packageName): bool
            {
                return in_array($packageName, $this->installedPackages, true);
            }
        };

        $state = new class($enabledPackages) implements PluginStateStore
        {
            /** @param list<string> $enabledPackages */
            public function __construct(private readonly array $enabledPackages) {}

            public function enabledPackages(): array
            {
                return $this->enabledPackages;
            }

            public function replaceEnabledPackages(array $packageNames): void {}
        };

        return new PluginManager($catalog, $packages, $state);
    }
}
