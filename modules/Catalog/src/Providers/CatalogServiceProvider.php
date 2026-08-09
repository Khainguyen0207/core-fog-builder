<?php

namespace Modules\Catalog\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Catalog\Admin\Tables\CategoryTable;
use Modules\Catalog\Admin\Tables\ServiceTable;

class CatalogServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'catalog');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('categories', CategoryTable::class);
        $tables->register('services', ServiceTable::class);
    }
}
