<?php

namespace App\Enums;

class CustomerMemberShipEnum extends BaseEnum
{
    const DEFAULT = 'default';

    const SILVER = 'silver';

    const GOLD = 'gold';

    const DIAMOND = 'diamond';

    public function getColor(): string
    {
        return match ($this->value) {
            self::SILVER => 'secondary',
            self::GOLD => 'warning',
            self::DIAMOND => 'info',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::SILVER => 'Thành viên Bạc',
            self::GOLD => 'Thành viên Vàng',
            self::DIAMOND => 'Thành viên Kim cương',
            default => 'Khách hàng thân thiết',
        };
    }
}
