<?php

namespace App\Http\Controllers\API;

use App\Enums\CouponTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\CouponEligibilityService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function __construct(
        private CouponEligibilityService $couponEligibilityService
    ) {}

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'services' => 'required|array',
            'services.*.service_id' => 'required|integer|exists:services,id',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (!$user || !$user->customer) {
            return response()->json([
                'error' => true,
                'message' => 'Giảm giá chỉ áp dụng cho người dùng đã đăng nhập.',
            ], 401);
        }

        try {
            $serviceIds = collect($request->input('services'))->pluck('service_id');
            $services = Service::whereIn('id', $serviceIds)->get();
            $price = $services->sum('price');

            $coupon = $this->couponEligibilityService->validate(
                $request->input('coupon_code'),
                $user->customer->id,
                $price
            );

            $value = (float) $coupon->value;

            $discount = match ($coupon->type->getValue()) {
                CouponTypeEnum::PERCENTAGE => round($price * $value / 100, 2),
                CouponTypeEnum::FIXED_AMOUNT => min($value, $price),
                default => 0,
            };

            return response()->json([
                'error' => false,
                'data' => [
                    'coupon_code' => $coupon->code,
                    'discount' => $discount,
                    'price' => $price,
                    'total_price' => $price - $discount,
                ],
                'message' => 'Mã giảm giá hợp lệ.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
