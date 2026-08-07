<?php

namespace App\Services;

use App\Enums\BasicStatusEnum;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use Carbon\Carbon;
use Exception;

class CouponEligibilityService
{
    /**
     * Validate coupon and return Coupon model if eligible.
     *
     * @throws Exception
     */
    public function validate(string $couponCode, int $customerId, float $price): Coupon
    {
        $coupon = Coupon::query()
            ->where('code', $couponCode)
            ->first();

        if (!$coupon) {
            throw new Exception('Mã giảm giá không tồn tại.');
        }

        $this->checkStatus($coupon);
        $this->checkDateRange($coupon);
        $this->checkMaxRedemptions($coupon);
        $this->checkMaxRedemptionsPerUser($coupon, $customerId);
        $this->checkMinOrderAmount($coupon, $price);

        return $coupon;
    }

    private function checkStatus(Coupon $coupon): void
    {
        if ($coupon->status->getValue() !== BasicStatusEnum::PUBLISHED) {
            throw new Exception('Mã giảm giá không còn hoạt động.');
        }
    }

    private function checkDateRange(Coupon $coupon): void
    {
        $now = Carbon::now();

        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            throw new Exception('Mã giảm giá chưa đến thời gian sử dụng.');
        }

        if ($coupon->ends_at && $now->gt($coupon->ends_at)) {
            throw new Exception('Mã giảm giá đã hết hạn.');
        }
    }

    private function checkMaxRedemptions(Coupon $coupon): void
    {
        if ($coupon->max_redemptions === null) {
            return;
        }

        $totalUsed = CouponRedemption::query()
            ->where('coupon_id', $coupon->id)
            ->count();

        if ($totalUsed >= $coupon->max_redemptions) {
            throw new Exception('Mã giảm giá đã hết lượt sử dụng.');
        }
    }

    private function checkMaxRedemptionsPerUser(Coupon $coupon, int $customerId): void
    {
        if ($coupon->max_redemptions_per_user === null) {
            return;
        }

        $userUsed = CouponRedemption::query()
            ->where('coupon_id', $coupon->id)
            ->where('customer_id', $customerId)
            ->count();

        if ($userUsed >= $coupon->max_redemptions_per_user) {
            throw new Exception('Bạn đã sử dụng hết lượt cho mã giảm giá này.');
        }
    }

    private function checkMinOrderAmount(Coupon $coupon, float $price): void
    {
        if ($coupon->min_order_amount === null) {
            return;
        }

        if ($price < (float) $coupon->min_order_amount) {
            throw new Exception(
                'Giá trị đơn hàng tối thiểu để sử dụng mã giảm giá là ' .
                    number_format($coupon->min_order_amount, 0, ',', '.') . 'đ.'
            );
        }
    }
}
