<?php

namespace Database\Seeders;

use App\Enums\UserGroupRoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->delete();

        $now = Carbon::now();

        User::query()->insert(
            [
                [
                    'id' => 1,
                    'email' => 'admin@admin.vn',
                    'password' => Hash::make('admin@admin.vn'),
                    'group_role' => UserGroupRoleEnum::ADMIN,
                    'created_at' => $now->copy()->subMonths(6),
                    'updated_at' => $now->copy()->subMonths(1),
                ],
                [
                    'id' => 2,
                    'email' => 'hoang.minh@rivercrane.vn',
                    'password' => Hash::make('123456'),
                    'group_role' => UserGroupRoleEnum::STAFF,
                    'created_at' => $now->copy()->subMonths(5),
                    'updated_at' => $now->copy()->subDays(10),
                ],
                [
                    'id' => 3,
                    'email' => 'thao.le@rivercrane.vn',
                    'password' => Hash::make('123456'),
                    'group_role' => UserGroupRoleEnum::STAFF,
                    'created_at' => $now->copy()->subMonths(4),
                    'updated_at' => $now->copy()->subDays(8),
                ],
                [
                    'id' => 4,
                    'email' => 'dat.nguyen@rivercrane.vn',
                    'password' => Hash::make('123456'),
                    'group_role' => UserGroupRoleEnum::STAFF,
                    'created_at' => $now->copy()->subMonths(3),
                    'updated_at' => $now->copy()->subDays(5),
                ],
                [
                    'id' => 5,
                    'email' => 'linh.pham@rivercrane.vn',
                    'password' => Hash::make('123456'),
                    'group_role' => UserGroupRoleEnum::STAFF,
                    'created_at' => $now->copy()->subMonths(2),
                    'updated_at' => $now->copy()->subDays(2),
                ],
                [
                    'id' => 6,
                    'email' => 'khoa.vo@rivercrane.vn',
                    'password' => Hash::make('123456'),
                    'group_role' => UserGroupRoleEnum::STAFF,
                    'created_at' => $now->copy()->subMonth(),
                    'updated_at' => $now,
                ],
            ]
        );
    }
}
