<?php

namespace App\Enums;

class BaseStatusEnum extends BaseEnum
{
    const ENABLED = 'enabled';

    const DISABLED = 'disabled';

    public function getColor(): string
    {
        $color = match ($this->value) {
            self::ENABLED => 'success',
            self::DISABLED => 'primary',
        };

        return $color ?? 'primary';
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::ENABLED => 'Enable',
            self::DISABLED => 'Disable',
            default => 'Unknown',
        };
    }
}
