<?php

namespace App\Http\Requests\Admin;

use App\Enums\CouponApplicableTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponApplicableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'coupon_id' => ['required', 'exists:coupons,id'],
            'applicable_type' => ['required', Rule::in(CouponApplicableTypeEnum::cases())],
        ];
    }
}
