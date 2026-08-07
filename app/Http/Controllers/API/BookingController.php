<?php

namespace App\Http\Controllers\API;

use App\Actions\CalendarBookingAction;
use App\Actions\CreateBookingAction;
use App\Enums\BookingStatusEnum;
use App\Http\Requests\API\BookingRequest;
use App\Http\Requests\API\FormatCalendarRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingRuleChecker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class BookingController
{
    public function getBookings()
    {
        $user = request()->user();
        $page = request("page", 1);
        $limit = request("limit", 10);

        $bookings = Booking::query()
            ->with('bookingServices', 'bookingServices.staffReview')
            ->where("customer_id", $user->customer->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->get();

        return response()->json([
            'error' => false,
            'data' => BookingResource::collection($bookings),
            'message' => 'Lấy danh sách đặt lịch thành công',
        ]);
    }

    public function getBooking($bookingCode)
    {
        $customerId = request('customer_id');

        $booking = Booking::query()
            ->with([
                'bookingServices.staff',
                'transaction',
                'customer',
                'assignedStaff',
                'bookingServices.staffReview'
            ])
            ->where('booking_code', $bookingCode)
            ->when($customerId, function ($query) use ($customerId) {
                $query->where('customer_id', $customerId);
            })
            ->firstOrFail();

        return response()->json([
            'error' => false,
            'data' => BookingResource::make($booking),
            'message' => 'Lấy thông tin đặt lịch thành công',
        ]);
    }

    public function create(BookingRequest $request, CreateBookingAction $action)
    {
        try {
            $booking = $action->handle($request->validated());

            return response()->json([
                'error' => false,
                'data' => BookingResource::make($booking),
                'message' => 'Tạo lịch hẹn thành công',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'data' => [],
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 422);
        }
    }

    public function getCalendar(FormatCalendarRequest $request, CalendarBookingAction $action, BookingRuleChecker $ruleChecker)
    {
        try {
            $day = Carbon::parse($request->input('day'));

            $services = $request->input('services');

            $services = json_decode($services, true);

            $calendar = $action->handle($day, $services);

            return response()->json([
                'error' => false,
                'data' => $calendar,
                'message' => 'Lấy lịch rảnh thành công',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'data' => [],
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|exists:bookings,booking_code',
        ]);

        $user = request()->user();

        $booking = Booking::query()
            ->where('booking_code', $request->booking_code)
            ->where('customer_id', $user->customer->id)
            ->firstOrFail();

        $status = $booking->status->getValue();

        if (!in_array($status, [BookingStatusEnum::PENDING, BookingStatusEnum::CONFIRMED])) {
            return response()->json([
                'error' => true,
                'data' => [],
                'message' => 'Lịch hẹn không thể hủy!',
            ], 422);
        }

        $booking->update(['status' => BookingStatusEnum::CANCELLED]);
        $booking->bookingServices()->update(['status' => BookingStatusEnum::CANCELLED]);

        return response()->json([
            'error' => false,
            'data' => BookingResource::make($booking->fresh()),
            'message' => 'Hủy lịch hẹn thành công',
        ]);
    }
}
