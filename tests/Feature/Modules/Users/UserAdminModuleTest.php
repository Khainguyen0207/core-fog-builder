<?php

namespace Tests\Feature\Modules\Users;

use App\Enums\UserGroupRoleEnum;
use App\Http\Middleware\IpManagerMiddleware;
use App\Models\User;
use App\Table\Configs\TableConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Modules\AdminUi\Http\Controllers\DataTableController;
use Modules\Customers\Admin\Tables\CustomerTable;
use Modules\Users\Admin\Tables\UserTable;
use Modules\Users\Http\Admin\Controllers\UserController;
use Tests\TestCase;

class UserAdminModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(IpManagerMiddleware::class);
    }

    public function test_module_routes_views_and_table_are_registered(): void
    {
        $this->assertSame(UserController::class.'@index', Route::getRoutes()->getByName('admin.users.index')->getActionName());
        $this->assertSame(DataTableController::class, Route::getRoutes()->getByName('admin.get-data')->getActionName());
        $this->assertSame(UserTable::class, app(TableConfig::class)->resolve('users'));
        $this->assertSame(CustomerTable::class, app(TableConfig::class)->resolve('customers'));
        $this->assertTrue(View::exists('users::forms.base'));
        $this->assertTrue(View::exists('users::tables.index'));
    }

    public function test_user_index_and_create_form_render_from_module_views(): void
    {
        $admin = $this->createUser('admin@example.com', UserGroupRoleEnum::ADMIN);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertViewIs('users::tables.index')
            ->assertSee('Users');

        $this->actingAs($admin)
            ->get(route('admin.users.create'))
            ->assertOk()
            ->assertViewIs('users::forms.base')
            ->assertSee(route('admin.users.store'), false)
            ->assertSee('Enter your email...');
    }

    public function test_data_table_endpoint_resolves_the_module_user_table(): void
    {
        $admin = $this->createUser('admin@example.com', UserGroupRoleEnum::ADMIN);
        $listedUser = $this->createUser('listed@example.com', UserGroupRoleEnum::STAFF);

        $this->actingAs($admin)
            ->postJson(route('admin.get-data', 'users'), [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search' => [
                    'value' => json_encode(['dataSearch' => []]),
                    'regex' => false,
                ],
            ])
            ->assertOk()
            ->assertJsonFragment([
                'id' => $listedUser->getKey(),
                'email' => $listedUser->email,
            ]);
    }

    public function test_user_can_be_created_with_the_existing_request_contract(): void
    {
        $admin = $this->createUser('admin@example.com', UserGroupRoleEnum::ADMIN);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'email' => 'new-user@example.com',
                'password' => 'secret12',
                'password_confirmation' => 'secret12',
                'group_role' => UserGroupRoleEnum::STAFF,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'User created successfully.');

        $this->assertDatabaseHas('users', [
            'email' => 'new-user@example.com',
            'group_role' => UserGroupRoleEnum::STAFF,
            'is_active' => 1,
        ]);
    }

    public function test_show_and_update_keep_the_existing_edit_form_behavior(): void
    {
        $admin = $this->createUser('admin@example.com', UserGroupRoleEnum::ADMIN);
        $user = $this->createUser('staff@example.com', UserGroupRoleEnum::STAFF);

        $this->actingAs($admin)
            ->get(route('admin.users.show', $user))
            ->assertOk()
            ->assertViewIs('users::forms.base')
            ->assertSee(route('admin.users.update', $user), false);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'email' => 'updated@example.com',
                'password' => '',
                'password_confirmation' => '',
                'group_role' => UserGroupRoleEnum::CUSTOMER,
                'is_active' => 0,
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'User updated successfully');

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'email' => 'updated@example.com',
            'group_role' => UserGroupRoleEnum::CUSTOMER,
            'is_active' => 0,
        ]);
    }

    public function test_destroy_keeps_current_user_protection_and_deletes_other_users(): void
    {
        $admin = $this->createUser('admin@example.com', UserGroupRoleEnum::ADMIN);
        $user = $this->createUser('staff@example.com', UserGroupRoleEnum::STAFF);

        $this->actingAs($admin)
            ->deleteJson(route('admin.users.destroy', $admin))
            ->assertOk()
            ->assertExactJson([
                'error' => true,
                'data' => null,
                'message' => 'Cannot delete the current user.',
            ]);

        $this->actingAs($admin)
            ->deleteJson(route('admin.users.destroy', $user))
            ->assertOk()
            ->assertExactJson([
                'error' => false,
                'data' => null,
                'message' => 'Delete user successfully',
            ]);

        $this->assertDatabaseHas('users', ['id' => $admin->getKey()]);
        $this->assertDatabaseMissing('users', ['id' => $user->getKey()]);
    }

    private function createUser(string $email, string $role): User
    {
        return User::query()->create([
            'email' => $email,
            'password' => 'password',
            'group_role' => $role,
            'is_active' => 1,
        ]);
    }
}
