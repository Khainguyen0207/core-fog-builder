<?php

namespace Modules\Users\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Users\Admin\Tables\UserTable;

class UsersServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'users');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('users', UserTable::class);
    }
}
