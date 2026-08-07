<?php

namespace App\Models;

use App\Enums\CouponApplicableTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponApplicable extends Model
{
    protected $table = 'coupon_applicables';

    protected $fillable = [
        'coupon_id',
        'applicable_type',
    ];

    protected $casts = [
        'applicable_type' => CouponApplicableTypeEnum::class,
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
