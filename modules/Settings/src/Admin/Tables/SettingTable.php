<?php

namespace Modules\Settings\Admin\Tables;

use App\Models\Setting;
use Modules\AdminUi\Tables\ModuleTable;

class SettingTable extends ModuleTable
{
    protected string $moduleView = 'settings::admin.pages.settings.index';

    public function setup(): static
    {
        parent::setup();

        return $this
            ->setNameTable('Settings')
            ->setModel(Setting::class)
            ->setTemplate('settings::admin.pages.settings.index')
            ->addColumns([

            ]);
    }
}
