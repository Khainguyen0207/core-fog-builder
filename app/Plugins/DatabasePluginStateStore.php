<?php

namespace App\Plugins;

use App\Models\Setting;
use App\Plugins\Contracts\PluginStateStore;
use Illuminate\Support\Facades\Schema;
use JsonException;
use Throwable;

class DatabasePluginStateStore implements PluginStateStore
{
    public const SETTING_KEY = 'enabled_plugins';

    public function enabledPackages(): array
    {
        try {
            if (! Schema::hasTable('settings')) {
                return [];
            }

            $value = Setting::query()
                ->where('key', self::SETTING_KEY)
                ->value('value');

            if (! is_string($value)) {
                return [];
            }

            $packages = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

            if (! is_array($packages)) {
                return [];
            }

            return array_values(array_unique(array_filter(
                $packages,
                fn (mixed $packageName): bool => is_string($packageName),
            )));
        } catch (JsonException) {
            return [];
        } catch (Throwable) {
            return [];
        }
    }
}
