<?php

namespace Modules\Settings\Http\Admin\Controllers;

use Modules\Settings\Admin\Panels\Forms\InformationSystemForm;
use Modules\Settings\Admin\Panels\Forms\SePayPanelForm;
use Modules\Settings\Admin\Panels\Forms\TelegramPanelForm;
use Modules\Settings\Admin\Panels\Forms\WorkTimeForm;
use Modules\Settings\Http\Controllers\Abstract\SettingController as Controller;

class SettingController extends Controller
{
    public function sePay()
    {
        return SePayPanelForm::make()->renderForm();
    }

    public function workTime()
    {
        return WorkTimeForm::make()->renderForm();
    }

    public function informationSystem()
    {
        return InformationSystemForm::make()->renderForm();
    }

    public function telegram()
    {
        return TelegramPanelForm::make()->renderForm();
    }
}
