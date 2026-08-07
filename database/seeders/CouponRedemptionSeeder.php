<?php

namespace Database\Seeders;

use App\Enums\CouponRedemptionStatusEnum;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponRedemptionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        CouponRedemption::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $coupons = Coupon::all();
        $customers = Customer::all();

        if ($coupons->isEmpty() || $customers->isEmpty()) {
            return;
        }

        // Create 20 random redemptions
        for ($i = 0; $i < 20; $i++) {
            $coupon = $coupons->random();
            $customer = $customers->random();

            CouponRedemption::create([
                'coupon_id' => $coupon->id,
                'customer_id' => $customer->id,
                'context_type' => fake()->randomElement(['booking', 'invoice', 'order']),
                'discount_amount' => fake()->randomFloat(2, 50000, 200000), // 50k - 200k
                'status' => fake()->randomElement(CouponRedemptionStatusEnum::values()),
                'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
