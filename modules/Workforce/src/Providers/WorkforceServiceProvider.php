<?php

namespace Modules\Workforce\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Workforce\Admin\Tables\StaffReviewTable;
use Modules\Workforce\Admin\Tables\StaffTable;

class WorkforceServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'workforce');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('staffs', StaffTable::class);
        $tables->register('staff-reviews', StaffReviewTable::class);
    }
}
