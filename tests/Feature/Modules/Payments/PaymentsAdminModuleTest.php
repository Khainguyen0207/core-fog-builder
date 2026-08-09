<?php

namespace Tests\Feature\Modules\Payments;

use Illuminate\Support\Facades\Route;
use Modules\Payments\Admin\Tables\TransactionTable;
use Modules\Payments\Http\Admin\Controllers\TransactionController;
use Modules\Shared\Tables\Registry\TableRegistry;
use Tests\TestCase;

class PaymentsAdminModuleTest extends TestCase
{
    public function test_payments_routes_are_registered_to_module_controller(): void
    {
        $routes = [
            'admin.transactions.index' => [TransactionController::class.'@index', 'admin/transactions', 'GET'],
            'admin.transactions.show' => [TransactionController::class.'@show', 'admin/transactions/{transaction}', 'GET'],
            'admin.transactions.update' => [TransactionController::class.'@update', 'admin/transactions/{transaction}', 'PUT'],
        ];

        foreach ($routes as $name => [$controller, $uri, $method]) {
            $route = Route::getRoutes()->getByName($name);

            $this->assertNotNull($route);
            $this->assertSame($controller, $route->getActionName());
            $this->assertSame($uri, $route->uri());
            $this->assertContains($method, $route->methods());
            $this->assertSame(['web', 'auth', 'ip.manager'], $route->gatherMiddleware());
        }
    }

    public function test_payments_table_and_views_are_registered(): void
    {
        $this->assertSame(TransactionTable::class, app(TableRegistry::class)->resolve('transactions'));
        $this->assertTrue(view()->exists('payments::forms.transaction.details'));
        $this->assertTrue(view()->exists('shared::tables.page'));
    }
}
