<?php

namespace App\Admin\Panels\Forms;

use App\Facades\SettingHelper;
use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Http\Controllers\Admin\StaffSettingController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

class ActiveStaffForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Setting::class, Setting::where('key', 'max_active_staff')->first())
            ->setTitle('Active Staff')
            ->setRoute(Route::put('admin.settings.active-staff.update')->name('admin.settings.active-staff.update'))
            ->add(
                'max_active_staff',
                InputField::class,
                InputField::make('max_active_staff')
                    ->setLabel('Max Active Staff')
                    ->setType('number')
                    ->setDefaultValue(SettingHelper::get('max_active_staff') ?? '')
                    ->setPlaceholder('Max Active Staff...')
                    ->isRequired()
            );
    }
}
