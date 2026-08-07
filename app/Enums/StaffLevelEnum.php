<?php

namespace App\Enums;

class StaffLevelEnum extends BaseEnum
{
    const TRAINEE = 'trainee';

    const JUNIOR = 'junior';

    const SENIOR = 'senior';

    const LEAD = 'lead';

    const SUPERVISOR = 'supervisor';

    public function getLabel(): string
    {
        return match ($this->value) {
            self::TRAINEE => 'Trainee',
            self::JUNIOR => 'Junior',
            self::SENIOR => 'Senior',
            self::LEAD => 'Lead',
            self::SUPERVISOR => 'Supervisor',
            default => 'Unknown',
        };
    }
}
