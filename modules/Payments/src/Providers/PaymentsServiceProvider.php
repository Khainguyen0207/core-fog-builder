<?php

namespace Modules\Payments\Providers;

use App\Events\TransactionFailedEvent;
use App\Jobs\CheckTransactionJob;
use App\Jobs\HandleExpiredTransactionsJob;
use App\Listeners\TransactionFailedListener;
use App\Plugins\PluginManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schedule;
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
        Event::listen(TransactionFailedEvent::class, TransactionFailedListener::class);

        Schedule::job(new CheckTransactionJob)
            ->everyTenSeconds()
            ->when(fn (): bool => app(PluginManager::class)->isEnabled('figure-admin/payments'));
        Schedule::job(new HandleExpiredTransactionsJob)
            ->everyTenMinutes()
            ->when(fn (): bool => app(PluginManager::class)->isEnabled('figure-admin/payments'));

        $tables->register('transactions', TransactionTable::class, 'figure-admin/payments');
        $menus->register('payments', [
            'name' => 'Payment', 'icon' => 'menu-icon tf-icons bx bx-wallet lst-none',
            'route' => 'admin.transactions.index', 'active' => ['admin.transactions.*'],
            'children' => [[
                'name' => 'Transactions', 'icon' => 'menu-icon tf-icons bx bx-money-withdraw me-2',
                'route' => 'admin.transactions.index', 'active' => ['admin.transactions.*'],
            ]],
        ], 200, 'figure-admin/payments');
    }
}
