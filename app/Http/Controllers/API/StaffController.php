<?php

namespace App\Http\Controllers\API;

use App\Enums\BookingStatusEnum;
use App\Http\Resources\StaffResource;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StaffController
{
    public function getStaffs()
    {
        $staffs = Staff::query()
            ->with(['user', 'staffReviews', 'services'])
            ->where('is_active', true)
            ->get();

        return response()->json([
            'error' => false,
            'data' => StaffResource::collection($staffs),
            'message' => 'Lấy danh sách nhân viên thành công.',
        ]);
    }

    public function getStaffbyID(Request $request)
    {
        $staffId = $request->query('staff_id');

        $staff = Staff::query()
            ->where('is_active', true)
            ->findOrFail($staffId);

        return response()->json([
            'error' => false,
            'data' => StaffResource::make($staff),
            'message' => "Lấy nhân viên ID #$staffId thành công"
        ]);
    }

    public function getStaffStats(int $staffId)
    {
        $staff = Staff::query()
            ->with(['user', 'staffReviews'])
            ->where('is_active', true)
            ->findOrFail($staffId);

        return response()->json([
            'error' => false,
            'data' => StaffResource::make($staff),
            'message' => 'Lấy thông tin nhân viên thành công.',
        ]);
    }

    public function getAvailableStaffs(Request $request)
    {
        $request->validate([
            'schedule_start' => 'required|date',
            'schedule_end' => 'required|date|after:schedule_start',
        ]);

        $start = Carbon::parse($request->get('schedule_start'))->toDateTimeString();
        $end = Carbon::parse($request->get('schedule_end'))->toDateTimeString();

        $bookingServices = BookingService::query()
            ->where('started_at', '<', $end)
            ->where('finished_at', '>', $start)
            ->where('status', '!=', BookingStatusEnum::CANCELLED)
            ->get();

        $busyStaffIds = $bookingServices->pluck('assigned_staff_id')->unique()->toArray();

        $staffs = Staff::query()
            ->where('is_active', true)
            ->get()
            ->each(fn(Staff $staff) => $staff->setAttribute('is_busy', in_array($staff->id, $busyStaffIds)));

        return response()->json([
            'error' => false,
            'data' => StaffResource::collection($staffs),
            'message' => 'Lấy danh sách nhân viên có thể làm việc thành công.',
        ]);
    }
}
