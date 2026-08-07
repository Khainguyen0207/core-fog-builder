<?php

namespace App\Enums;

class PaymentProviderEnum extends BaseEnum
{
    const MOMO = 'momo';

    const SEPAY = 'sepay';

    const BINANCE = 'binance';

    public function getLabel(): string
    {
        return match ($this->value) {
            self::MOMO => 'Momo',
            self::SEPAY => 'SePay',
            self::BINANCE => 'Binance',
            default => 'Unknown payment provider'
        };
    }
}
