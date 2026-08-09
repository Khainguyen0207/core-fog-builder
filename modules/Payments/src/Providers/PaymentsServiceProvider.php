<?php

namespace Modules\Payments\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Payments\Admin\Tables\TransactionTable;

class PaymentsServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'payments');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('transactions', TransactionTable::class);
    }
}
