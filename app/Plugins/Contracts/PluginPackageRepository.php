<?php

namespace App\Plugins\Contracts;

interface PluginPackageRepository
{
    public function isInstalled(string $packageName): bool;
}
