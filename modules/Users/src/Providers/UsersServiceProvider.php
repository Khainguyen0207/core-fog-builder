<?php

namespace Modules\Users\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;
use Modules\Users\Admin\Tables\UserTable;

class UsersServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'users');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('users', UserTable::class);
        $menus->register('users', [
            'name' => 'Users', 'icon' => 'menu-icon tf-icons bx bx-user',
            'route' => 'admin.users.index', 'active' => ['admin.users.*'],
        ], 400);
    }
}
