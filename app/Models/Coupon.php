<?php

namespace App\Models;

use App\Enums\BasicStatusEnum;
use App\Enums\CouponTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',
        'value',
        'starts_at',
        'ends_at',
        'max_redemptions',
        'max_redemptions_per_user',
        'min_order_amount',
        'status',
    ];

    protected $casts = [
        'type' => CouponTypeEnum::class,
        'value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'max_redemptions' => 'integer',
        'max_redemptions_per_user' => 'integer',
        'min_order_amount' => 'decimal:2',
        'status' => BasicStatusEnum::class,
    ];

    public function applicables(): HasMany
    {
        return $this->hasMany(CouponApplicable::class, 'coupon_id');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class, 'coupon_id');
    }
}
