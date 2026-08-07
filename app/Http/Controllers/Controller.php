<?php

namespace App\Http\Controllers;

use App\Table\Configs\TableConfig;
use App\Table\Factory\TableFactory;
use Illuminate\Http\Request;

abstract class Controller
{
    public function getDataTable(Request $request, string $table, TableConfig $config)
    {
        return (new TableFactory($config))->make($table)->setup()->getDataTable();
    }
}
