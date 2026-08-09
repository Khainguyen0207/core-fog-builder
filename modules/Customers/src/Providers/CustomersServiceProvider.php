<?php

namespace Modules\Customers\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Customers\Admin\Tables\CustomerTable;
use Modules\Customers\Admin\Tables\MembershipSettingTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;

class CustomersServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'customers');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('customers', CustomerTable::class);
        $tables->register('membership-settings', MembershipSettingTable::class);
        $menus->register('customers', [
            'name' => 'Customers', 'icon' => 'menu-icon tf-icons bx bx-group',
            'route' => 'admin.customers.index', 'active' => ['admin.customers.*', 'admin.membership-settings.*'],
            'children' => [
                ['name' => 'Customers', 'route' => 'admin.customers.index', 'active' => ['admin.customers.*']],
                ['name' => 'Membership Settings', 'route' => 'admin.membership-settings.index', 'active' => ['admin.membership-settings.*']],
            ],
        ], 300);
    }
}
