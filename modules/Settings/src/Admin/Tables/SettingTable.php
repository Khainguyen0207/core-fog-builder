<?php

namespace Modules\Settings\Admin\Tables;

use App\Models\Setting;
use Modules\Shared\Tables\Table;

class SettingTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setNameTable('Settings')
            ->setModel(Setting::class)
            ->addColumns([

            ]);
    }
}
