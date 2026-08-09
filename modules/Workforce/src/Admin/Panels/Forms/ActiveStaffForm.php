<?php

namespace Modules\Workforce\Admin\Panels\Forms;

use App\Facades\SettingHelper;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Form;

class ActiveStaffForm extends Form
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Setting::class, Setting::where('key', 'max_active_staff')->first())
            ->setTemplate('workforce::forms.base')
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
