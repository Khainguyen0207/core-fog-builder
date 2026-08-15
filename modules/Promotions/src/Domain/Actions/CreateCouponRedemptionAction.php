<?php

namespace App\Actions;

use App\Enums\CouponRedemptionStatusEnum;
use App\Models\Coupon;
use App\Models\CouponRedemption;

class CreateCouponRedemptionAction
{
    public function handle(Coupon $coupon, int $customerId, float $discountAmount): CouponRedemption
    {
        return CouponRedemption::query()->create([
            'coupon_id' => $coupon->id,
            'customer_id' => $customerId,
            'context_type' => 'booking',
            'discount_amount' => $discountAmount,
            'status' => CouponRedemptionStatusEnum::APPLIED,
        ]);
    }
}
