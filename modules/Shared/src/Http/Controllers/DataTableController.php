<?php

namespace Modules\Shared\Http\Controllers;

use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Modules\Shared\Tables\Factory\TableFactory;

class DataTableController
{
    public function __invoke(string $table, TableFactory $tables): JsonResponse
    {
        try {
            $resolvedTable = $tables->make($table);
        } catch (InvalidArgumentException) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'The requested table is not available.',
            ], 404);
        }

        return $resolvedTable->setup()->getDataTable();
    }
}
