<?php

namespace App\Plugins\Contracts;

interface PluginStateStore
{
    /** @return list<string> */
    public function enabledPackages(): array;
}
