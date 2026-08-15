<?php

namespace Modules\Catalog\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Catalog\Admin\Tables\CategoryTable;
use Modules\Catalog\Admin\Tables\ServiceTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;

class CatalogServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'catalog');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('categories', CategoryTable::class, 'figure-admin/catalog');
        $tables->register('services', ServiceTable::class, 'figure-admin/catalog');
        $menus->register('catalog', [
            'name' => 'Catalog', 'icon' => 'menu-icon tf-icons bx bx-package',
            'route' => 'admin.services.index', 'active' => ['admin.services.*', 'admin.categories.*'],
            'children' => [
                ['name' => 'Services', 'route' => 'admin.services.index', 'active' => ['admin.services.*']],
                ['name' => 'Categories', 'route' => 'admin.categories.index', 'active' => ['admin.categories.*']],
            ],
        ], 600, 'figure-admin/catalog');
    }
}
