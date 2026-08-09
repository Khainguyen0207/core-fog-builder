<?php

namespace Modules\Customers\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Customers\Admin\Tables\CustomerTable;
use Modules\Customers\Admin\Tables\MembershipSettingTable;

class CustomersServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'customers');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('customers', CustomerTable::class);
        $tables->register('membership-settings', MembershipSettingTable::class);
    }
}
