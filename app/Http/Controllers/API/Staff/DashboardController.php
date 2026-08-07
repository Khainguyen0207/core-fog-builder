<?php

namespace App\Http\Controllers\API\Staff;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $staff = request()->user()->staff;
        $staffId = $staff->id;
        $now = Carbon::now();

        $baseQuery = fn() => Booking::whereHas('bookingServices', fn($q) => $q->where('assigned_staff_id', $staffId));

        $todayCount = $baseQuery()->whereDate('scheduled_start', $now->toDateString())->count();
        $weekCount = $baseQuery()->whereBetween('scheduled_start', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->count();
        $monthCount = $baseQuery()->whereBetween('scheduled_start', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->count();

        $upcomingBookings = $baseQuery()
            ->where('status', BookingStatusEnum::CONFIRMED)
            ->where('scheduled_start', '>=', $now)
            ->orderBy('scheduled_start')
            ->with(['bookingServices.service', 'customer'])
            ->limit(5)
            ->get();

        return response()->json([
            'error' => false,
            'data' => [
                'today_count' => $todayCount,
                'week_count' => $weekCount,
                'month_count' => $monthCount,
                'rate' => $staff->rate,
                'upcoming_bookings' => BookingResource::collection($upcomingBookings),
            ],
            'message' => 'Lấy thông tin dashboard thành công',
        ]);
    }
}
