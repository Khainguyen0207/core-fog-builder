<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Table\Configs\TableConfig;
use App\Table\Factory\TableFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkDeleteController extends Controller
{
    public function __construct(
        private TableConfig $tableConfig
    ) {}

    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'resource' => 'required|string',
        ]);

        $resource = $request->input('resource');
        $ids = $request->input('ids');

        try {

            $tableFactory = new TableFactory($this->tableConfig);
            $table = $tableFactory->make($resource)->setup();
            $model = $table->getModel();

            if (! $model) {
                return response()->json([
                    'error' => true,
                    'message' => "Resource '{$resource}' không có model.",
                ], 400);
            }

            DB::beginTransaction();

            $deletedCount = $model->newQuery()->whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => "Đã xóa thành công {$deletedCount} bản ghi.",
                'data' => [
                    'deleted_count' => $deletedCount,
                    'ids' => $ids,
                ],
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => true,
                'message' => "Resource '{$resource}' không được hỗ trợ.",
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => 'Lỗi khi xóa: ' . $e->getMessage(),
            ], 500);
        }
    }
}
