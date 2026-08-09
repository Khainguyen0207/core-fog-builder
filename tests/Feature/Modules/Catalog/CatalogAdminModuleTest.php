<?php

namespace Tests\Feature\Modules\Catalog;

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Admin\Tables\CategoryTable;
use Modules\Catalog\Admin\Tables\ServiceTable;
use Modules\Catalog\Http\Admin\Controllers\CategoryController;
use Modules\Catalog\Http\Admin\Controllers\ServiceController;
use Modules\Shared\Tables\Registry\TableRegistry;
use Tests\TestCase;

class CatalogAdminModuleTest extends TestCase
{
    public function test_catalog_routes_views_and_tables_are_registered(): void
    {
        $this->assertSame(CategoryController::class.'@index', Route::getRoutes()->getByName('admin.categories.index')->getActionName());
        $this->assertSame(ServiceController::class.'@index', Route::getRoutes()->getByName('admin.services.index')->getActionName());
        $this->assertSame(CategoryTable::class, app(TableRegistry::class)->resolve('categories'));
        $this->assertSame(ServiceTable::class, app(TableRegistry::class)->resolve('services'));
        $this->assertTrue(view()->exists('shared::tables.page'));
    }
}
