<?php

namespace App\Enums;

class UserGroupRoleEnum extends BaseEnum
{
    const ADMIN = 'admin';

    const STAFF = 'staff';

    const CUSTOMER = 'customer';

    public function getColor(): string
    {
        return match ($this->value) {
            self::ADMIN => 'success',
            self::CUSTOMER => 'info',
            self::STAFF => 'warning',
            default => 'primary',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::ADMIN => 'Admin',
            self::STAFF => 'Staff',
            self::CUSTOMER => 'Customer',
            default => 'Default',
        };
    }
}
