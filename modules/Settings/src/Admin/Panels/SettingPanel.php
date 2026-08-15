<?php

namespace Modules\Settings\Admin\Panels;

use App\Plugins\PluginManager;
use Modules\Shared\Panels\Panel;
use Modules\Shared\Panels\PanelSection;

class SettingPanel extends PanelSection
{
    public function __construct(private readonly PluginManager $plugins) {}

    public function setup(): static
    {
        parent::setup();

        $panels = [
            Panel::make('work_time')
                ->setName('Work Time')
                ->setDescription('Set operating hours for your system.')
                ->setUrl(route('admin.settings.work-time'))
                ->setButtonLabel('Setup'),
            Panel::make('information_system')
                ->setName('Information System Setting')
                ->setDescription('Information system settings configuration')
                ->setUrl(route('admin.settings.information-system')),
            Panel::make('plugins')
                ->setName('Plugins')
                ->setDescription('Enable optional features and review their dependencies.')
                ->setUrl(route('admin.settings.plugins'))
                ->setIcon('bx bx-extension'),
        ];

        if ($this->plugins->isEnabled('figure-admin/workforce')) {
            $panels[] = Panel::make('active_staff')
                ->setName('Active Staff')
                ->setDescription('Set the number of employees working simultaneously.')
                ->setUrl(route('admin.settings.active-staff.index'));
        }

        if ($this->plugins->isEnabled('figure-admin/payments')) {
            $panels[] = Panel::make('sea_pay_setting')
                ->setName('SePay')
                ->setDescription('SePay settings configuration')
                ->setUrl(route('admin.settings.sepay'));
        }

        if ($this->plugins->isEnabled('figure-admin/communications')) {
            $panels[] = Panel::make('telegram')
                ->setName('Telegram Bot')
                ->setDescription('Configure Telegram Bot to receive order notifications.')
                ->setUrl(route('admin.settings.telegram'));
        }

        return $this
            ->setNameTable('Settings')
            ->setTemplate('settings::admin.pages.settings.index')
            ->addPanels($panels);
    }
}
