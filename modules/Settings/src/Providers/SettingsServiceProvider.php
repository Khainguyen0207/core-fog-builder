<?php

namespace Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Settings\Admin\Tables\SettingTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'settings');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('base table', SettingTable::class);
        $menus->register('settings', [
            'name' => 'System Settings', 'icon' => 'menu-icon tf-icons bx bx-cog',
            'route' => 'admin.settings.index', 'active' => ['admin.settings.*'],
        ], 1200);
    }
}
