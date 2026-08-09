<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Shared\Menu\MenuRegistry;

class DashboardServiceProvider extends ServiceProvider
{
    public function boot(MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'dashboard');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $menus->register('dashboard', [
            'name' => 'Analytics', 'icon' => 'menu-icon tf-icons bx bx-bar-chart-square',
            'route' => 'admin.dashboard.index', 'active' => ['admin.dashboard.*'],
        ], 100);
    }
}
