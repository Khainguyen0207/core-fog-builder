<?php

namespace Tests\Feature\Plugins;

use App\Enums\UserGroupRoleEnum;
use App\Http\Middleware\IpManagerMiddleware;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CorePluginIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(IpManagerMiddleware::class);
    }

    public function test_core_settings_hide_and_gate_optional_panels(): void
    {
        $admin = $this->createUser('admin@example.test', UserGroupRoleEnum::ADMIN);

        $this->actingAs($admin)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Work Time')
            ->assertSee('Information System Setting')
            ->assertSee('Plugins')
            ->assertDontSee('Active Staff')
            ->assertDontSee('SePay')
            ->assertDontSee('Telegram Bot');

        $this->actingAs($admin)->get(route('admin.settings.sepay'))->assertNotFound();
        $this->actingAs($admin)->get(route('admin.settings.telegram'))->assertNotFound();
    }

    public function test_customer_creation_does_not_query_booking_data_when_booking_is_disabled(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        app(CustomerService::class)->create([
            'email' => 'customer@example.test',
            'password' => 'secret12',
            'name' => 'Core Customer',
            'phone' => '0900000001',
        ]);

        $bookingQueries = array_filter(
            DB::getQueryLog(),
            fn (array $query): bool => str_contains($query['query'], 'bookings'),
        );

        $this->assertSame([], array_values($bookingQueries));
    }

    public function test_users_table_does_not_query_workforce_data_when_workforce_is_disabled(): void
    {
        $admin = $this->createUser('admin@example.test', UserGroupRoleEnum::ADMIN);
        $this->createUser('staff@example.test', UserGroupRoleEnum::STAFF);

        DB::flushQueryLog();
        DB::enableQueryLog();

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
            ->assertOk();

        $workforceQueries = array_filter(
            DB::getQueryLog(),
            fn (array $query): bool => str_contains($query['query'], 'staffs'),
        );

        $this->assertSame([], array_values($workforceQueries));
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
