<?php

namespace App\Plugins;

use App\Plugins\Contracts\PluginPackageRepository;
use App\Plugins\Contracts\PluginStateStore;

final class PluginManager
{
    /** @var list<string>|null */
    private ?array $requestedPackages = null;

    /** @var array<string, PluginStatus> */
    private array $statuses = [];

    public function __construct(
        private readonly PluginCatalog $catalog,
        private readonly PluginPackageRepository $packages,
        private readonly PluginStateStore $state,
    ) {}

    /** @return list<PluginStatus> */
    public function statuses(): array
    {
        return array_map(
            fn (PluginDefinition $definition): PluginStatus => $this->status($definition->packageName),
            $this->catalog->all(),
        );
    }

    public function status(string $packageName): PluginStatus
    {
        if (isset($this->statuses[$packageName])) {
            return $this->statuses[$packageName];
        }

        $definition = $this->catalog->get($packageName);
        $installed = $this->packages->isInstalled($packageName);
        $requested = $definition->core || in_array($packageName, $this->requestedPackages(), true);

        if (! $installed) {
            return $this->statuses[$packageName] = new PluginStatus(
                $definition,
                false,
                $requested,
                false,
                PluginStatus::PACKAGE_MISSING,
            );
        }

        if (! $requested) {
            return $this->statuses[$packageName] = new PluginStatus(
                $definition,
                true,
                false,
                false,
                PluginStatus::DISABLED,
            );
        }

        $blockers = array_values(array_filter(
            $definition->dependencies,
            fn (string $dependency): bool => ! $this->status($dependency)->enabled,
        ));

        if ($blockers !== []) {
            return $this->statuses[$packageName] = new PluginStatus(
                $definition,
                true,
                true,
                false,
                PluginStatus::DEPENDENCY_UNAVAILABLE,
                $blockers,
            );
        }

        return $this->statuses[$packageName] = new PluginStatus(
            $definition,
            true,
            true,
            true,
            PluginStatus::ENABLED,
        );
    }

    public function isEnabled(string $packageName): bool
    {
        return $this->status($packageName)->enabled;
    }

    public function forgetCachedState(): void
    {
        $this->requestedPackages = null;
        $this->statuses = [];
    }

    /** @return list<string> */
    private function requestedPackages(): array
    {
        return $this->requestedPackages ??= array_values(array_unique(array_filter(
            $this->state->enabledPackages(),
            fn (string $packageName): bool => $this->catalog->has($packageName),
        )));
    }
}
