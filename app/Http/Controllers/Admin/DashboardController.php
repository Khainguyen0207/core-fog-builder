<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\TransactionStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\IpLog;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $timezone = config('app.timezone');
        $now = Carbon::now($timezone);

        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();
        $lastWeekStart = $weekStart->copy()->subWeek();
        $lastWeekEnd = $weekEnd->copy()->subWeek();

        $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        $kpis = $this->buildKpis($weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd);
        $charts = $this->buildCharts($weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd, $labels);
        $topServices = $this->getTopServices($weekStart, $weekEnd);
        $topCategories = $this->getTopCategories($weekStart, $weekEnd);
        $recentActivities = $this->getRecentActivities();
        $paymentStats = $this->getPaymentStats($weekStart, $weekEnd);
        $topCountries = $this->getTopCountries();

        return view('admin.pages.dashboard.index', compact(
            'kpis',
            'charts',
            'topServices',
            'topCategories',
            'recentActivities',
            'paymentStats',
            'topCountries',
            'weekStart',
            'weekEnd'
        ));
    }

    private function getRecentActivities(): array
    {
        return Booking::select('id', 'booking_code', 'customer_name', 'status', 'scheduled_start', 'created_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'customer_name' => $booking->customer_name,
                    'status' => $booking->status->getValue(),
                    'status_label' => $booking->status->getLabel(),
                    'status_color' => $booking->status->getColor(),
                    'scheduled_start' => Carbon::parse($booking->scheduled_start)->format('d/m/Y H:i'),
                    'created_at' => Carbon::parse($booking->created_at)->diffForHumans(),
                ];
            })
            ->toArray();
    }

    private function buildKpis(Carbon $weekStart, Carbon $weekEnd, Carbon $lastWeekStart, Carbon $lastWeekEnd): array
    {

        $pendingBookings = Booking::where('status', BookingStatusEnum::PENDING)
            ->whereBetween('scheduled_start', [$weekStart, $weekEnd])
            ->count();

        $bookingThisWeek = Booking::query()
            ->whereBetween('scheduled_start', [$weekStart, $weekEnd])
            ->count();

        $revenueThisWeek = Booking::where('status', BookingStatusEnum::DONE)
            ->whereBetween('scheduled_start', [$weekStart, $weekEnd])
            ->sum('total_price');

        $expectedRevenueThisWeek = Booking::whereNotIn('status', [BookingStatusEnum::PENDING, BookingStatusEnum::CANCELLED])
            ->whereBetween('scheduled_start', [$weekStart, $weekEnd])
            ->sum('total_price');


        $pendingBookingsLastWeek = Booking::whereNotIn('status', [BookingStatusEnum::PENDING, BookingStatusEnum::CANCELLED])
            ->whereBetween('scheduled_start', [$lastWeekStart, $lastWeekEnd])
            ->count();

        $bookingLastWeek = Booking::query()
            ->whereBetween('scheduled_start', [$lastWeekStart, $lastWeekEnd])
            ->count();

        $revenueLastWeek = Booking::where('status', BookingStatusEnum::DONE)
            ->whereBetween('scheduled_start', [$lastWeekStart, $lastWeekEnd])
            ->sum('total_price');

        $expectedRevenueLastWeek = Booking::whereIn('status', [BookingStatusEnum::PENDING, BookingStatusEnum::CONFIRMED])
            ->whereBetween('scheduled_start', [$lastWeekStart, $lastWeekEnd])
            ->sum('total_price');

        return [
            'bookingThisWeek' => [
                'value' => $bookingThisWeek,
                'growth' => $this->calculateGrowth($bookingThisWeek, $bookingLastWeek),
            ],
            'pendingBookings' => [
                'value' => $pendingBookings,
                'growth' => $this->calculateGrowth($pendingBookings, $pendingBookingsLastWeek),
            ],
            'revenueThisWeek' => [
                'value' => $revenueThisWeek,
                'growth' => $this->calculateGrowth($revenueThisWeek, $revenueLastWeek),
            ],
            'expectedRevenueThisWeek' => [
                'value' => $expectedRevenueThisWeek,
                'growth' => $this->calculateGrowth($expectedRevenueThisWeek, $expectedRevenueLastWeek),
            ],
        ];
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    private function buildCharts(Carbon $weekStart, Carbon $weekEnd, Carbon $lastWeekStart, Carbon $lastWeekEnd, array $labels): array
    {
        $bookingsThisWeek = $this->getBookingCountsByDay($weekStart, $weekEnd);
        $bookingsLastWeek = $this->getBookingCountsByDay($lastWeekStart, $lastWeekEnd);

        $revenueThisWeek = $this->getRevenueByDay($weekStart, $weekEnd);
        $revenueLastWeek = $this->getRevenueByDay($lastWeekStart, $lastWeekEnd);

        return [
            'bookingsWeekly' => [
                'labels' => $labels,
                'series' => [
                    ['name' => 'This Week', 'data' => $bookingsThisWeek],
                    ['name' => 'Last Week', 'data' => $bookingsLastWeek],
                ],
            ],
            'revenueWeekly' => [
                'labels' => $labels,
                'series' => [
                    ['name' => 'This Week', 'data' => $revenueThisWeek],
                    ['name' => 'Last Week', 'data' => $revenueLastWeek],
                ],
            ],
        ];
    }

    private function getBookingCountsByDay(Carbon $start, Carbon $end): array
    {
        $data = Booking::selectRaw('DAYOFWEEK(scheduled_start) as dow, COUNT(*) as cnt')
            ->whereBetween('scheduled_start', [$start, $end])
            ->groupBy('dow')
            ->pluck('cnt', 'dow')
            ->toArray();

        $result = [];
        for ($i = 2; $i <= 8; $i++) {
            $dayIndex = $i > 7 ? 1 : $i;
            $result[] = $data[$dayIndex] ?? 0;
        }

        return $result;
    }

    private function getRevenueByDay(Carbon $start, Carbon $end): array
    {
        $data = Booking::selectRaw('DAYOFWEEK(scheduled_start) as dow, SUM(total_price) as total')
            ->where('status', BookingStatusEnum::DONE)
            ->whereBetween('scheduled_start', [$start, $end])
            ->groupBy('dow')
            ->pluck('total', 'dow')
            ->toArray();

        $result = [];
        for ($i = 2; $i <= 8; $i++) {
            $dayIndex = $i > 7 ? 1 : $i;
            $result[] = (int) ($data[$dayIndex] ?? 0);
        }

        return $result;
    }

    private function getTopServices(Carbon $weekStart, Carbon $weekEnd): array
    {
        return BookingService::select('service_id', 'service_name')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(price) as revenue')
            ->whereHas('booking', function ($q) use ($weekStart, $weekEnd) {
                $q->whereBetween('scheduled_start', [$weekStart, $weekEnd]);
            })
            ->groupBy('service_id', 'service_name')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getTopCategories(Carbon $weekStart, Carbon $weekEnd): array
    {
        return BookingService::join('services', 'booking_services.service_id', '=', 'services.id')
            ->join('categories', 'services.category_id', '=', 'categories.id')
            ->join('bookings', 'booking_services.booking_id', '=', 'bookings.id')
            ->select('categories.id', 'categories.name')
            ->selectRaw('COUNT(booking_services.id) as count')
            ->selectRaw('SUM(booking_services.price) as revenue')
            ->whereBetween('bookings.scheduled_start', [$weekStart, $weekEnd])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getPaymentStats(Carbon $start, Carbon $end): array
    {
        $payLaterCount = Booking::where('payment_method', PaymentMethodEnum::PAY_LATER)
            ->whereBetween('scheduled_start', [$start, $end])
            ->count();

        $payLaterRevenue = Booking::where('payment_method', PaymentMethodEnum::PAY_LATER)
            ->whereNotIn('status', [BookingStatusEnum::PENDING, BookingStatusEnum::CANCELLED])
            ->whereBetween('scheduled_start', [$start, $end])
            ->sum('total_price');

        $transactions = Transaction::select('provider_code', DB::raw('count(*) as total'))
            ->where('status', TransactionStatusEnum::COMPLETED)
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('provider_code')
            ->get();

        $labels = ['Pay Later'];
        $series = [number_format(floatval($payLaterRevenue), 0, ',', '.') . ' VND'];
        $icons = ['wallet.png'];

        foreach ($transactions as $transaction) {
            $label = match ($transaction->provider_code->getValue()) {
                'momo' => 'Momo',
                'sepay' => 'SePay',
                'binance' => 'Binance',
                default => ucfirst($transaction->provider_code->getValue()),
            };

            $icon = match ($transaction->provider_code->getValue()) {
                'momo' => 'momo.png',
                'sepay' => 'sepay.webp',
                'binance' => 'binance.png',
                default => 'wallet.png',
            };

            $bankTransferRevenue = Transaction::where('provider_code', $transaction->provider_code->getValue())
                ->where('status', TransactionStatusEnum::COMPLETED)
                ->whereBetween('created_at', [$start, $end])
                ->whereHas('booking', function ($q) use ($start, $end) {
                    $q->whereBetween('scheduled_start', [$start, $end]);
                })
                ->sum('amount');

            $icons[] = $icon;
            $labels[] = $label;
            $series[] = number_format(floatval($bankTransferRevenue), 0, ',', '.') . ' VND';
        }

        return [
            'labels' => $labels,
            'series' => $series,
            'icons' => $icons,
        ];
    }

    private function getTopCountries(): array
    {
        $total = IpLog::count();

        if ($total === 0) {
            return [];
        }

        return IpLog::select('iso_code', 'country', DB::raw('COUNT(*) as count'))
            ->groupBy('iso_code', 'country')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($total) {
                return [
                    'iso_code' => strtolower($item->iso_code),
                    'country' => $item->country,
                    'count' => $item->count,
                    'percentage' => round(($item->count / $total) * 100, 2),
                ];
            })
            ->toArray();
    }
}
