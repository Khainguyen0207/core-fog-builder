<?php

namespace App\Plugins;

use InvalidArgumentException;

final readonly class PluginDefinition
{
    /** @param list<string> $dependencies */
    public function __construct(
        public string $packageName,
        public string $displayName,
        public bool $core,
        public array $dependencies,
    ) {
        if (! preg_match('/^[a-z0-9_.-]+\/[a-z0-9_.-]+$/', $this->packageName)) {
            throw new InvalidArgumentException("Invalid Composer package name [{$this->packageName}].");
        }

        if ($this->displayName === '') {
            throw new InvalidArgumentException("Plugin [{$this->packageName}] requires a display name.");
        }

        if (in_array($this->packageName, $this->dependencies, true)) {
            throw new InvalidArgumentException("Plugin [{$this->packageName}] cannot depend on itself.");
        }
    }
}
