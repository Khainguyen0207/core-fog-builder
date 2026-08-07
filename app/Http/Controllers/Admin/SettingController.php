<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Panels\Forms\ActiveStaffForm;
use App\Admin\Panels\Forms\InformationSystemForm;
use App\Admin\Panels\Forms\SePayPanelForm;
use App\Admin\Panels\Forms\TelegramPanelForm;
use App\Admin\Panels\Forms\WorkTimeForm;
use App\Http\Controllers\Abstract\SettingController as Controller;

class SettingController extends Controller
{
    public function sePay()
    {
        return SePayPanelForm::make()->renderForm();
    }

    public function activeStaff()
    {
        return ActiveStaffForm::make()->renderForm();
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
