<?php

namespace App\Plugins;

use App\Plugins\Contracts\PluginPackageRepository;
use Composer\InstalledVersions;

class ComposerPluginPackageRepository implements PluginPackageRepository
{
    public function isInstalled(string $packageName): bool
    {
        return InstalledVersions::isInstalled($packageName);
    }
}
