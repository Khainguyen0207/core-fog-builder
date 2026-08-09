<?php

namespace Tests\Feature\Modules\Auth;

use App\Enums\UserGroupRoleEnum;
use App\Http\Middleware\IpManagerMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Admin\Controllers\AuthenticationController;
use Tests\TestCase;

class AuthAdminModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_admin_routes_are_registered_to_module_controller(): void
    {
        $routes = [
            'login' => [AuthenticationController::class.'@login', 'login', 'GET', ['web', 'guest']],
            'login.authenticate' => [AuthenticationController::class.'@authenticate', 'login', 'POST', ['web', 'guest']],
            'admin.logout' => [AuthenticationController::class.'@logout', 'admin/logout', 'GET', ['web', 'auth', 'ip.manager']],
        ];

        foreach ($routes as $name => [$controller, $uri, $method, $middleware]) {
            $route = Route::getRoutes()->getByName($name);

            $this->assertNotNull($route);
            $this->assertSame($controller, $route->getActionName());
            $this->assertSame($uri, $route->uri());
            $this->assertContains($method, $route->methods());
            $this->assertSame($middleware, $route->gatherMiddleware());
        }
    }

    public function test_login_view_is_registered_and_rendered(): void
    {
        $this->assertTrue(view()->exists('auth::admin.pages.auth.login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth::admin.pages.auth.login');
    }

    public function test_active_admin_can_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
            'group_role' => UserGroupRoleEnum::ADMIN,
            'is_active' => 1,
        ]);

        $this->post(route('login.authenticate'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard.index'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->last_login_at);
        $this->assertSame('127.0.0.1', $user->fresh()->last_login_ip);
    }

    public function test_invalid_admin_credentials_return_the_existing_error(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
            'group_role' => UserGroupRoleEnum::ADMIN,
            'is_active' => 1,
        ]);

        $this->from(route('login'))->post(route('login.authenticate'), [
            'email' => 'admin@example.com',
            'password' => 'incorrect',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('error');

        $this->assertGuest();
    }

    public function test_logout_ends_authentication_and_redirects_to_login(): void
    {
        $user = User::factory()->create([
            'group_role' => UserGroupRoleEnum::ADMIN,
            'is_active' => 1,
        ]);

        $this->withoutMiddleware(IpManagerMiddleware::class)
            ->actingAs($user)
            ->get(route('admin.logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
