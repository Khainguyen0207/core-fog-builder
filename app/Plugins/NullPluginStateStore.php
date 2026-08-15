<?php

namespace App\Plugins;

use App\Plugins\Contracts\PluginStateStore;

class NullPluginStateStore implements PluginStateStore
{
    public function enabledPackages(): array
    {
        return [];
    }

    public function replaceEnabledPackages(array $packageNames): void {}
}
