<?php

namespace Database\Seeders;

use App\Enums\BasicStatusEnum;
use App\Enums\CouponTypeEnum;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'id' => 5,
                'code' => 'MEMBERSHIP_GOLD',
                'type' => CouponTypeEnum::PERCENTAGE,
                'value' => 5.00,
                'starts_at' => '2026-01-01 01:00:00',
                'ends_at' => '2026-12-31 12:59:00',
                'max_redemptions' => null,
                'max_redemptions_per_user' => null,
                'min_order_amount' => null,
                'status' => BasicStatusEnum::PUBLISHED,
                'created_at' => '2026-02-10 09:55:55',
                'updated_at' => '2026-02-10 09:55:55',
            ],
            [
                'id' => 6,
                'code' => 'MEMBERSHIP_SLIVER',
                'type' => CouponTypeEnum::PERCENTAGE,
                'value' => 2.00,
                'starts_at' => '2026-01-01 01:00:00',
                'ends_at' => '2026-12-31 12:59:00',
                'max_redemptions' => null,
                'max_redemptions_per_user' => null,
                'min_order_amount' => null,
                'status' => BasicStatusEnum::PUBLISHED,
                'created_at' => '2026-02-10 09:57:29',
                'updated_at' => '2026-02-10 09:57:29',
            ],
            [
                'id' => 7,
                'code' => 'MEMBERSHIP_DIAMOND',
                'type' => CouponTypeEnum::PERCENTAGE,
                'value' => 10.00,
                'starts_at' => '2026-01-01 01:00:00',
                'ends_at' => '2026-12-31 12:59:00',
                'max_redemptions' => null,
                'max_redemptions_per_user' => null,
                'min_order_amount' => null,
                'status' => BasicStatusEnum::PUBLISHED,
                'created_at' => '2026-02-10 09:58:25',
                'updated_at' => '2026-02-10 09:58:25',
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Coupon::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Coupon::query()->insert($coupons);
    }
}
