<?php

namespace App\Http\Controllers\API\Staff;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingServiceResource;
use App\Models\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingServiceController extends Controller
{
    public function updateStatus(Request $request, BookingService $bookingService): JsonResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(BookingStatusEnum::values())],
            'note' => ['nullable', 'string', 'max:65535'],
        ]);

        if ($bookingService->assigned_staff_id !== $request->user()->staff->id) {
            return response()->json([
                'error' => true,
                'message' => 'Không có quyền cập nhật trạng thái',
            ], 403);
        }

        $bookingService->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Cập nhật trạng thái thành công',
        ], 204);
    }
}
