<?php

namespace App\Models;

use App\Enums\CouponRedemptionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponRedemption extends Model
{
    protected $table = 'coupon_redemptions';

    protected $fillable = [
        'coupon_id',
        'customer_id',
        'context_type',
        'discount_amount',
        'status',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'status' => CouponRedemptionStatusEnum::class,
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
