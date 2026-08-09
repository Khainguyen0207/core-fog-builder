<?php

namespace Modules\AdminUi\Tables;

use App\Table\BaseTable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

abstract class ModuleTable extends BaseTable
{
    protected string $moduleView;

    public function renderTable(): View|Application|Factory|\Illuminate\View\View
    {
        $this->setup();

        return view($this->moduleView, [
            'table' => $this,
            'title' => $this->getNameTable() ?? 'Example App',
            'name' => $this->getName(),
            'data' => '',
            'dataTables' => $this->getModel() ?? [],
        ]);
    }
}
