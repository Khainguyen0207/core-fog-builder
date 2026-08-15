<?php

namespace App\Plugins;

use Illuminate\Contracts\Container\Container;
use Modules\Shared\Registry\Contracts\RegistrationVisibility;
use Throwable;

class PluginRegistrationVisibility implements RegistrationVisibility
{
    public function __construct(private readonly Container $container) {}

    public function allows(string $owner): bool
    {
        try {
            return $this->container->make(PluginManager::class)->isEnabled($owner);
        } catch (Throwable) {
            return false;
        }
    }
}
