<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ConfigHelper
{
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = 'config_' . $key;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $config = Setting::where('key', $key)->first();
        $value = $config ? $config->value : $default;

        Cache::put($cacheKey, $value, now()->addDay());

        return $value;
    }

    public static function set(string $key, string $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::put('config_' . $key, $value, now()->addDay());
    }

    public static function clearCache(): void
    {
        Cache::flush();
    }

    public static function getByPrefix(string $prefix): array
    {
        $configs = Setting::where('key', 'LIKE', $prefix . '%')->get();
        $result = [];

        foreach ($configs as $config) {
            $key = str_replace($prefix, '', $config->key);
            $result[$key] = $config->value;
        }

        return $result;
    }

    public static function getAll()
    {
        return Setting::all();
    }
}
