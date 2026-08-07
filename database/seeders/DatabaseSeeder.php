<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
            StaffSeeder::class,
            SettingSeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            MembershipSettingSeeder::class,
            BlogSeeder::class,
            PaymentSettingSeeder::class,
            CouponSeeder::class,
            CouponApplicableSeeder::class,
            CouponRedemptionSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
