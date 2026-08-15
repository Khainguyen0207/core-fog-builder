<?php

namespace App\Plugins;

use InvalidArgumentException;

final class PluginCatalog
{
    /** @var array<string, PluginDefinition> */
    private array $definitions = [];

    /** @param array<string, array{display_name: string, core: bool, dependencies: list<string>}> $plugins */
    public function __construct(array $plugins)
    {
        foreach ($plugins as $packageName => $plugin) {
            $this->definitions[$packageName] = new PluginDefinition(
                $packageName,
                $plugin['display_name'],
                $plugin['core'],
                array_values(array_unique($plugin['dependencies'])),
            );
        }

        $this->validateDependencies();
        $this->validateAcyclic();
    }

    /** @return list<PluginDefinition> */
    public function all(): array
    {
        return array_values($this->definitions);
    }

    /** @return list<PluginDefinition> */
    public function optional(): array
    {
        return array_values(array_filter(
            $this->definitions,
            fn (PluginDefinition $definition): bool => ! $definition->core,
        ));
    }

    public function has(string $packageName): bool
    {
        return isset($this->definitions[$packageName]);
    }

    public function get(string $packageName): PluginDefinition
    {
        return $this->definitions[$packageName]
            ?? throw new InvalidArgumentException("Unknown plugin [{$packageName}].");
    }

    private function validateDependencies(): void
    {
        foreach ($this->definitions as $definition) {
            foreach ($definition->dependencies as $dependency) {
                if (! isset($this->definitions[$dependency])) {
                    throw new InvalidArgumentException(
                        "Plugin [{$definition->packageName}] has unknown dependency [{$dependency}].",
                    );
                }
            }
        }
    }

    private function validateAcyclic(): void
    {
        $visited = [];
        $visiting = [];

        foreach (array_keys($this->definitions) as $packageName) {
            $this->visit($packageName, $visited, $visiting);
        }
    }

    /** @param array<string, bool> $visited @param array<string, bool> $visiting */
    private function visit(string $packageName, array &$visited, array &$visiting): void
    {
        if (isset($visited[$packageName])) {
            return;
        }

        if (isset($visiting[$packageName])) {
            throw new InvalidArgumentException("Plugin dependency cycle detected at [{$packageName}].");
        }

        $visiting[$packageName] = true;

        foreach ($this->definitions[$packageName]->dependencies as $dependency) {
            $this->visit($dependency, $visited, $visiting);
        }

        unset($visiting[$packageName]);
        $visited[$packageName] = true;
    }
}
