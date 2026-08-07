<?php

namespace App\Admin\Panels;

use App\Panel\AbstractPanelSection;
use App\Panel\BasePanel;

class SettingPanel extends AbstractPanelSection
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setNameTable('Settings')
            ->setTemplate('pages.settings.index')
            ->addPanels([
                BasePanel::make('work_time')
                    ->setName('Work Time')
                    ->setDescription('Set operating hours for your system.')
                    ->setUrl(route('admin.settings.work-time'))
                    ->setButtonLabel('Setup'),

                BasePanel::make('work_time')
                    ->setName('Active Staff')
                    ->setDescription('Set the number of employees working simultaneously.')
                    ->setUrl(route('admin.settings.active-staff.index')),

                BasePanel::make('sea_pay_setting')
                    ->setName('SePay')
                    ->setDescription('SePay settings configuration')
                    ->setUrl(route('admin.settings.sepay')),

                BasePanel::make('information_system')
                    ->setName('Information System Setting')
                    ->setDescription('Information system settings configuration')
                    ->setUrl(route('admin.settings.information-system')),

                BasePanel::make('telegram')
                    ->setName('Telegram Bot')
                    ->setDescription('Configure Telegram Bot to receive order notifications.')
                    ->setUrl(route('admin.settings.telegram')),
            ]);
    }
}
