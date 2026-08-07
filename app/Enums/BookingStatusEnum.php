<?php

namespace App\Enums;

class BookingStatusEnum extends BaseEnum
{
    const PENDING = 'pending';

    const CONFIRMED = 'confirmed';

    const IN_PROGRESS = 'in_progress';

    const DONE = 'done';

    const CANCELLED = 'cancelled';

    public function getColor(): string
    {
        return match ($this->value) {
            self::PENDING => 'secondary',
            self::CONFIRMED => 'info',
            self::IN_PROGRESS => 'warning',
            self::DONE => 'success',
            self::CANCELLED => 'danger',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::PENDING => 'Pending Confirmation',
            self::CONFIRMED => 'Confirmed',
            self::IN_PROGRESS => 'In Progress',
            self::DONE => 'Completed',
            self::CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }
}
