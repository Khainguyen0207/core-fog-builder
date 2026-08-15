<?php

namespace App\Enums;

class CouponTypeEnum extends BaseEnum
{
    const PERCENTAGE = 'percentage';

    const FIXED_AMOUNT = 'fixed_amount';

    public function getColor(): string
    {
        return match ($this->value) {
            self::PERCENTAGE => 'info',
            self::FIXED_AMOUNT => 'warning',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::PERCENTAGE => 'Percentage',
            self::FIXED_AMOUNT => 'Fixed Amount',
            default => 'Unknown',
        };
    }
}
