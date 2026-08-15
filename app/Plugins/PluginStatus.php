<?php

namespace App\Plugins;

final readonly class PluginStatus
{
    public const ENABLED = 'enabled';

    public const DISABLED = 'disabled';

    public const PACKAGE_MISSING = 'package_missing';

    public const DEPENDENCY_UNAVAILABLE = 'dependency_unavailable';

    /** @param list<string> $blockers */
    public function __construct(
        public PluginDefinition $definition,
        public bool $installed,
        public bool $requested,
        public bool $enabled,
        public string $reason,
        public array $blockers = [],
    ) {}
}
