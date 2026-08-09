<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Shared\Menu\MenuRegistry;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'auth');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');
        $menus->register('auth.logout', [
            'name' => 'Logout', 'icon' => 'menu-icon tf-icons bx bx-power-off',
            'route' => 'admin.logout', 'active' => ['admin.logout'],
        ], 1300);
    }
}
