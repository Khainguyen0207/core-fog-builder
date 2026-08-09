<?php

namespace Modules\Settings\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Settings\Admin\Tables\SettingTable;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'settings');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('base table', SettingTable::class);
    }
}
