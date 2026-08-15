<?php

namespace Modules\Shared\Registry\Contracts;

interface RegistrationVisibility
{
    public function allows(string $owner): bool;
}
