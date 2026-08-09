<?php

namespace Modules\Shared\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Tables\Factory\TableFactory;

class DataTableController
{
    public function __invoke(string $table, TableFactory $tables): JsonResponse
    {
        return $tables->make($table)->setup()->getDataTable();
    }
}
