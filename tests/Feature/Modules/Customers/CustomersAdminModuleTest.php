<?php

namespace Tests\Feature\Modules\Customers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\Facades\Route;
use Modules\Customers\Admin\Tables\CustomerTable;
use Modules\Customers\Admin\Tables\MembershipSettingTable;
use Modules\Customers\Http\Admin\Controllers\CustomerController;
use Modules\Customers\Http\Admin\Controllers\MembershipSettingController;
use Tests\TestCase;

class CustomersAdminModuleTest extends TestCase
{
    public function test_customers_routes_views_and_tables_are_registered(): void
    {
        $this->assertSame(CustomerController::class.'@index', Route::getRoutes()->getByName('admin.customers.index')->getActionName());
        $this->assertSame(MembershipSettingController::class.'@index', Route::getRoutes()->getByName('admin.membership-settings.index')->getActionName());
        $this->assertSame(CustomerTable::class, app(TableConfig::class)->resolve('customers'));
        $this->assertSame(MembershipSettingTable::class, app(TableConfig::class)->resolve('membership-settings'));
        $this->assertTrue(view()->exists('customers::forms.base'));
        $this->assertTrue(view()->exists('customers::tables.index'));
    }
}
