<?php

namespace App\Http\Controllers\API;

use App\Enums\BaseStatusEnum;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController
{
    public function getServices(): JsonResponse
    {
        $services = Service::query()
            ->where('status', BaseStatusEnum::ENABLED)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json([
            'error' => false,
            'data' => ServiceResource::collection($services),
            'message' => 'Lấy danh sách dịch vụ thành công',
        ]);
    }
}
