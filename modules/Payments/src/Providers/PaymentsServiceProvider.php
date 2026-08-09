<?php

namespace Modules\Payments\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Payments\Admin\Tables\TransactionTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;

class PaymentsServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'payments');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('transactions', TransactionTable::class);
        $menus->register('payments', [
            'name' => 'Payment', 'icon' => 'menu-icon tf-icons bx bx-wallet lst-none',
            'route' => 'admin.transactions.index', 'active' => ['admin.transactions.*'],
            'children' => [[
                'name' => 'Transactions', 'icon' => 'menu-icon tf-icons bx bx-money-withdraw me-2',
                'route' => 'admin.transactions.index', 'active' => ['admin.transactions.*'],
            ]],
        ], 200);
    }
}
