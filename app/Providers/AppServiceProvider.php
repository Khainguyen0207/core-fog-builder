<?php

namespace App\Providers;

use App\Plugins\ComposerPluginPackageRepository;
use App\Plugins\Contracts\PluginPackageRepository;
use App\Plugins\Contracts\PluginStateStore;
use App\Plugins\DatabasePluginStateStore;
use App\Plugins\PluginCatalog;
use App\Plugins\PluginManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PluginCatalog::class, fn (): PluginCatalog => new PluginCatalog(
            config('figure-admin-plugins.plugins', []),
        ));
        $this->app->singleton(PluginPackageRepository::class, ComposerPluginPackageRepository::class);
        $this->app->singleton(PluginStateStore::class, DatabasePluginStateStore::class);
        $this->app->scoped(PluginManager::class);
    }

    public function boot(): void {}
}
