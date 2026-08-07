<?php

namespace App\Http\Requests\Admin;

use App\Enums\CouponRedemptionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRedemptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'coupon_id' => ['required', 'exists:coupons,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'context_type' => ['required', 'string', 'max:64'],
            'discount_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(CouponRedemptionStatusEnum::cases())],
        ];
    }
}
