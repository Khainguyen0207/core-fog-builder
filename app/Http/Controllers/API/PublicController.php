<?php

namespace App\Http\Controllers\API;

use App\Facades\SettingHelper;
use App\Http\Resources\BookingResource;
use App\Http\Resources\SystemSettingResource;
use App\Models\Booking;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicController
{
    public function getSystemSettings()
    {
        $excludeKeys = [
            'sepay_api_token',
            'max_active_staff',
        ];

        $settings = Setting::query()
            ->whereNotIn('key', $excludeKeys)
            ->pluck('value', 'key')
            ->toArray();

        return response()->json([
            'error' => false,
            'data' => new SystemSettingResource($settings),
            'message' => 'Lấy cài đặt hệ thống thành công',
        ]);
    }

    public function getCalendarWork(Request $request)
    {
        if ($request->has('date')) {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
            ]);

            $date = Carbon::parse($request->input('date'));
            $workTimes = get_work_schedule_by_date($date);
        } else {
            $workTimes = [];
            $startOfWeek = Carbon::now()->startOfWeek();

            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i);
                $key = get_key_setting_work_schedule_by_day($day);
                $workTimes[$key] = SettingHelper::get($key);
            }
        }

        return response()->json([
            'error' => false,
            'data' => $workTimes,
            'message' => 'Lấy lịch làm việc thành công',
        ]);
    }

    public function getBookingByBookingCode(Request $request)
    {
        $booking = Booking::query()
            ->with('bookingServices.staff.user', 'bookingServices.service', 'bookingServices.staffReview')
            ->where('booking_code', $request->query('booking_code'))
            ->orWhere('customer_phone', $request->query('phone'))
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $booking) {
            return response()->json([
                'error' => true,
                'data' => [],
                'message' => 'Không tìm thấy lịch hẹn',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'data' => BookingResource::make($booking),
            'message' => 'Lấy thông tin lịch hẹn thành công',
        ]);
    }
}
