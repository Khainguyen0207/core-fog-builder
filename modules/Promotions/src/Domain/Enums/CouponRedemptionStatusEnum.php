<?php

namespace App\Enums;

class CouponRedemptionStatusEnum extends BaseEnum
{
    const APPLIED = 'applied';

    const CANCELLED = 'cancelled';

    const REFUNDED = 'refunded';

    public function getColor(): string
    {
        return match ($this->value) {
            self::APPLIED => 'success',
            self::CANCELLED => 'danger',
            self::REFUNDED => 'warning',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::APPLIED => 'Applied',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
            default => 'Unknown',
        };
    }
}
