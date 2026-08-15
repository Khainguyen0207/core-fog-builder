<?php

namespace App\Enums;

class CouponApplicableTypeEnum extends BaseEnum
{
    const BOOKING = 'booking';

    const MEMBERSHIP_PLAN = 'membership_plan';

    public function getColor(): string
    {
        return match ($this->value) {
            self::BOOKING => 'info',
            self::MEMBERSHIP_PLAN => 'success',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::BOOKING => 'Booking',
            self::MEMBERSHIP_PLAN => 'Membership Plan',
            default => 'Unknown',
        };
    }
}
