<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.pages.calendar.index');
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $query = Booking::query();

        if ($start) {
            $query->where('scheduled_start', '>=', $start);
        }
        if ($end) {
            $query->where('scheduled_start', '<=', $end);
        }

        $bookings = $query->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->customer_name ?? $booking->booking_code,
                'start' => $booking->scheduled_start->format('Y-m-d H:i'),
                'end' => $booking->estimated_end->format('Y-m-d H:i'),
                'extendedProps' => [
                    'status' => $booking->status->getValue(),
                    'color' => $booking->status->getColor(),
                    'booking_code' => $booking->booking_code,
                    'start' => $booking->scheduled_start->format('H:i'),
                    'end' => $booking->estimated_end->format('H:i'),
                ],
            ];
        });

        return response()->json($events);
    }
}
