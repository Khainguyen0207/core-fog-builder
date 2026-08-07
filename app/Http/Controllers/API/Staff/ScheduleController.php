<?php

namespace App\Http\Controllers\API\Staff;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\StaffScheduleResource;
use App\Models\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'sometimes|in:today,week,month',
        ]);

        $staff = $request->user()->staff;
        $period = $request->query('period', 'today');
        $now = Carbon::now();

        $query = BookingService::query()
            ->with(['booking', 'service'])
            ->whereHas('booking', function ($q) {
                $q->whereNotIn('status', [BookingStatusEnum::CANCELLED, BookingStatusEnum::PENDING]);
            })
            ->where('assigned_staff_id', $staff->id);

        $query = match ($period) {
            'today' => $query->whereDate('started_at', $now->toDateString()),
            'week' => $query->whereBetween('started_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]),
            'month' => $query->whereBetween('started_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]),
        };

        $bookings = $query
            ->orderBy('started_at')
            ->with(['booking.customer'])
            ->paginate(5);

        return response()->json([
            'error' => false,
            'data' => StaffScheduleResource::collection($bookings)->response()->getData(true),
            'message' => 'Lấy lịch làm việc thành công',
        ]);
    }
}
