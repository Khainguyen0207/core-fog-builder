<?php

namespace Modules\Shared\Registry;

use Modules\Shared\Registry\Contracts\RegistrationVisibility;

class AllowAllRegistrationVisibility implements RegistrationVisibility
{
    public function allows(string $owner): bool
    {
        return true;
    }
}
