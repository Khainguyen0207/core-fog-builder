<?php

namespace App\Facades;

use App\Helpers\ConfigHelper;
use Illuminate\Support\Facades\Facade;

class SettingHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ConfigHelper::class;
    }
}
