<?php

namespace App\Enums;

class TransactionStatusEnum extends BaseEnum
{
    const PENDING = 'pending';

    const COMPLETED = 'completed';

    const FAILED = 'failed';

    const EXPIRED = 'expired';

    const REFUNDED = 'refunded';

    public function getLabel(): string
    {
        return match ($this->value) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::EXPIRED => 'Expired',
            self::REFUNDED => 'Refunded',
            default => 'Unknown',
        };
    }

    public function getColor(): string
    {
        return match ($this->value) {
            self::PENDING => 'warning',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
            self::EXPIRED => 'danger',
            self::REFUNDED => 'info',
            default => 'secondary',
        };
    }
}
