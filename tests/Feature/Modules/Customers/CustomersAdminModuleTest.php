<?php

namespace Tests\Feature\Modules\Customers;

use App\Enums\BasicStatusEnum;
use App\Enums\UserGroupRoleEnum;
use App\Http\Middleware\IpManagerMiddleware;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Modules\Customers\Admin\Tables\CustomerTable;
use Modules\Customers\Admin\Tables\MembershipSettingTable;
use Modules\Customers\Http\Admin\Controllers\CustomerController;
use Modules\Customers\Http\Admin\Controllers\MembershipSettingController;
use Modules\Shared\Tables\Registry\TableRegistry;
use Tests\TestCase;

class CustomersAdminModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(IpManagerMiddleware::class);
    }

    public function test_customers_routes_views_and_tables_are_registered(): void
    {
        $this->assertSame(CustomerController::class.'@index', Route::getRoutes()->getByName('admin.customers.index')->getActionName());
        $this->assertSame(MembershipSettingController::class.'@index', Route::getRoutes()->getByName('admin.membership-settings.index')->getActionName());
        $this->assertSame(CustomerTable::class, app(TableRegistry::class)->resolve('customers'));
        $this->assertSame(MembershipSettingTable::class, app(TableRegistry::class)->resolve('membership-settings'));
        $this->assertTrue(view()->exists('customers::forms.base'));
        $this->assertTrue(view()->exists('shared::tables.page'));
    }

    public function test_membership_setting_can_be_created_from_the_admin_table(): void
    {
        $admin = User::query()->create([
            'email' => 'admin@example.com',
            'password' => 'secret12',
            'group_role' => UserGroupRoleEnum::ADMIN,
            'is_active' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.membership-settings.index'))
            ->assertOk()
            ->assertSee(route('admin.membership-settings.create'), false);

        $this->actingAs($admin)
            ->get(route('admin.membership-settings.create'))
            ->assertOk()
            ->assertSee('Enter membership code...');

        $this->actingAs($admin)
            ->post(route('admin.membership-settings.store'), [
                'membership_code' => 'platinum',
                'name' => 'Platinum',
                'min_points' => 5000,
                'status' => BasicStatusEnum::PUBLISHED,
                'description' => 'Platinum membership',
            ])
            ->assertRedirect(route('admin.membership-settings.index'))
            ->assertSessionHas('success', 'Membership setting created successfully.');

        $this->assertDatabaseHas('membership_settings', [
            'membership_code' => 'platinum',
            'name' => 'Platinum',
        ]);
    }

    public function test_customer_can_be_deleted_with_the_json_envelope_used_by_the_admin_table(): void
    {
        $admin = User::query()->create([
            'email' => 'admin@example.com',
            'password' => 'secret12',
            'group_role' => UserGroupRoleEnum::ADMIN,
            'is_active' => 1,
        ]);
        $customer = Customer::query()->create([
            'name' => 'Customer To Delete',
            'phone' => '0900000001',
        ]);

        $this->actingAs($admin)
            ->deleteJson(route('admin.customers.destroy', $customer))
            ->assertOk()
            ->assertExactJson([
                'error' => false,
                'data' => null,
                'message' => 'Customer deleted successfully',
            ]);

        $this->assertDatabaseMissing('customers', ['id' => $customer->getKey()]);
    }
}
