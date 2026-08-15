<?php

namespace Database\Seeders;

use App\Enums\CouponApplicableTypeEnum;
use App\Models\Coupon;
use App\Models\CouponApplicable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponApplicableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        CouponApplicable::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $coupons = Coupon::all();

        foreach ($coupons as $coupon) {
            CouponApplicable::create([
                'coupon_id' => $coupon->id,
                'applicable_type' => CouponApplicableTypeEnum::MEMBERSHIP_PLAN,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
