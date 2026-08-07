<?php

namespace App\Actions;

use App\Enums\BookingStatusEnum;
use App\Enums\CouponTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Booking;
use App\Models\Coupon;

class ApplyCouponToBookingAction
{
    public function handle(Booking $booking, Coupon $coupon): Booking
    {
        $price = (float) $booking->price;
        $discount = $this->calculateDiscount($coupon, $price);
        $totalPrice = $price - $discount;

        $booking->update([
            'discount' => $discount,
            'total_price' => $totalPrice,
            'coupon_code' => $coupon->code,
            'status' =>  $totalPrice == 0 ? BookingStatusEnum::CONFIRMED : BookingStatusEnum::PENDING,
        ]);

        $booking->load('transaction');

        if ($booking->transaction) {
            $booking->transaction->update([
                'amount' => $totalPrice,
                'status' => $totalPrice == 0 ? TransactionStatusEnum::COMPLETED : TransactionStatusEnum::PENDING,
            ]);
        }

        return $booking;
    }

    private function calculateDiscount(Coupon $coupon, float $price): float
    {
        $value = (float) $coupon->value;

        return match ($coupon->type->getValue()) {
            CouponTypeEnum::PERCENTAGE => round($price * $value / 100, 2),
            CouponTypeEnum::FIXED_AMOUNT => min($value, $price),
            default => 0,
        };
    }
}
