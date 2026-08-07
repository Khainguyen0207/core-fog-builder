<?php

namespace App\Enums;

class BasicStatusEnum extends BaseEnum
{
    const PUBLISHED = 'published';

    const DRAFT = 'draft';

    public function getColor(): string
    {
        $color = match ($this->value) {
            self::PUBLISHED => 'success',
            self::DRAFT => 'secondary',
        };

        return $color ?? 'primary';
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::PUBLISHED => 'Published',
            self::DRAFT => 'Draft',
            default => 'Unknown',
        };
    }
}
