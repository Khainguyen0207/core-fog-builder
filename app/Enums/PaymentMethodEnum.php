<?php

namespace App\Enums;

class PaymentMethodEnum extends BaseEnum
{
    const BANK_TRANSFER = 'bank_transfer';

    const PAY_LATER = 'pay_later';

    public function getLabel(): string
    {
        return match ($this->value) {
            self::BANK_TRANSFER => 'Bank Transfer',
            self::PAY_LATER => 'Pay Later',
            default => 'Unknown',
        };
    }
}
