<?php

namespace Modules\AdminUi\Http\Controllers;

use App\Table\Factory\TableFactory;
use Illuminate\Http\JsonResponse;

class DataTableController
{
    public function __invoke(string $table, TableFactory $tables): JsonResponse
    {
        return $tables->make($table)->setup()->getDataTable();
    }
}
