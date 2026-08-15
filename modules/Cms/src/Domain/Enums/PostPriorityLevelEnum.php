<?php

namespace App\Enums;

class PostPriorityLevelEnum extends BaseEnum
{
    const NORMAL = 'normal';

    const INFO = 'info';

    const IMPORTANT = 'important';

    const URGENT = 'urgent';

    public function getColor(): string
    {
        return match ($this->value) {
            self::NORMAL => 'secondary',
            self::INFO => 'info',
            self::IMPORTANT => 'warning',
            self::URGENT => 'danger',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::NORMAL => 'Normal',
            self::INFO => 'Info',
            self::IMPORTANT => 'Important',
            self::URGENT => 'Urgent',
            default => 'Unknown',
        };
    }
}
