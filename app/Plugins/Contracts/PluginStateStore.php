<?php

namespace App\Plugins\Contracts;

interface PluginStateStore
{
    /** @return list<string> */
    public function enabledPackages(): array;

    /** @param list<string> $packageNames */
    public function replaceEnabledPackages(array $packageNames): void;
}
