<?php

namespace App\Enums;

class PostTypeEnum extends BaseEnum
{
    const INTERNAL = 'internal';

    const COMMUNITY = 'community';

    public function getColor(): string
    {
        return match ($this->value) {
            self::INTERNAL => 'warning',
            self::COMMUNITY => 'info',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::INTERNAL => 'Internal',
            self::COMMUNITY => 'Community',
            default => 'Unknown',
        };
    }
}
