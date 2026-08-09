<?php

namespace Modules\Settings\Admin\Panels\Forms;

use App\Facades\SettingHelper;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Form;

class WorkTimeForm extends Form
{
    public function setup(): static
    {
        parent::setup();
        $key = get_key_setting_work_schedule();

        $fields = [];

        foreach (Carbon::getDays() as $value) {
            $keyDay = Str::lower($value);

            $fields[] = [
                'name' => $key.$keyDay,
                'type' => InputField::class,
                'field' => InputField::make($key.$keyDay)
                    ->setLabel($value)
                    ->setDefaultValue(SettingHelper::get($key.$keyDay) ?? '')
                    ->setAttributes([
                        'class' => 'form-control daterangepicker-range',
                    ])
                    ->helperText('If it\'s a holiday, set 00:00 - 00:00.')
                    ->setPlaceholder('Enter '.$value),
            ];
        }

        return $this
            ->model(Setting::class)
            ->setTemplate('settings::forms.base')
            ->setTitle('Work Time')
            ->addMore($fields);
    }
}
