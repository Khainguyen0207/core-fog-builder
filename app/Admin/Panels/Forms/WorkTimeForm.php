<?php

namespace App\Admin\Panels\Forms;

use App\Facades\SettingHelper;
use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;

class WorkTimeForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();
        $key = get_key_setting_work_schedule();

        $fields = [];

        foreach (Carbon::getDays() as $value) {
            $keyDay = Str::lower($value);

            $fields[] = [
                'name' => $key . $keyDay,
                'type' => InputField::class,
                'field' => InputField::make($key . $keyDay)
                    ->setLabel($value)
                    ->setDefaultValue(SettingHelper::get($key . $keyDay) ?? '')
                    ->setAttributes([
                        'class' => 'form-control daterangepicker-range'
                    ])
                    ->helperText('If it\'s a holiday, set 00:00 - 00:00.')
                    ->setPlaceholder('Enter ' . $value),
            ];
        }

        return $this
            ->model(Setting::class)
            ->setTitle('Work Time')
            ->addMore($fields);
    }
}
