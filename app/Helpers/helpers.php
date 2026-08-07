<?php

use App\Facades\SettingHelper;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

if (! function_exists('get_day_of_the_week')) {
    function get_day_of_the_week(Carbon $date): string
    {
        return match ($date->getDaysFromStartOfWeek()) {
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            default => 'sunday',
        };
    }
}

if (! function_exists('get_key_setting_work_schedule')) {
    function get_key_setting_work_schedule(): string
    {
        return 'work_time_';
    }
}

if (! function_exists('get_key_setting_work_schedule_by_day')) {
    function get_key_setting_work_schedule_by_day(Carbon $date): string
    {
        return get_key_setting_work_schedule() . Str::lower($date->getTranslatedDayName());
    }
}

if (! function_exists('get_work_schedule_by_date')) {
    function get_work_schedule_by_date(Carbon $date)
    {
        $keyDay = get_key_setting_work_schedule_by_day($date);

        $work_schedule = SettingHelper::get($keyDay);
        $work_schedule = explode(' - ', $work_schedule);

        return $work_schedule;
    }
}

if (! function_exists('get_bank_name_label')) {
    function get_bank_name_label(string $bankName): string
    {
        $banks = collect(config('banks'))->mapWithKeys(fn($value, $key) => [$key => $value])->toArray();

        return Arr::get($banks, $bankName, $bankName);
    }
}
