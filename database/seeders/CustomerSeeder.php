<?php

namespace Database\Seeders;

use App\Enums\CustomerMemberShipEnum;
use App\Enums\UserGroupRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customers')->delete();

        $password = Hash::make('123456');
        $now = now();

        for ($i = 10; $i <= 90; $i++) {
            $email = 'customer' . $i . '@example.com';

            $user = User::query()->create([
                'email' => $email,
                'password' => $password,
                'group_role' => UserGroupRoleEnum::CUSTOMER,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('customers')->insert([
                'name' => 'Customer ' . $i,
                'phone' => '07775227' . $i,
                'user_id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
