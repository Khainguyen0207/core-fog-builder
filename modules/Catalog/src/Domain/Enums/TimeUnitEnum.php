<?php

namespace App\Enums;

class TimeUnitEnum extends BaseEnum
{
    const MINUTE = 'minute';

    // const HOUR = 'hour';

    public function getColor(): string
    {
        return match ($this->value) {
            self::MINUTE => 'success',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::MINUTE => 'Minute',
            default => 'Unknown',
        };
    }
}
