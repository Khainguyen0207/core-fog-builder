<?php

namespace App\Admin\Tables;

use App\Models\Setting;
use App\Table\BaseTable;

class SettingTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setNameTable('Settings')
            ->setModel(Setting::class)
            ->setTemplate('admin.pages.settings.index')
            ->addColumns([

            ]);
    }
}
