<?php

namespace App\Actions;

use App\Services\BookingAvailabilityService;
use App\Services\BookingRuleChecker;
use App\Services\CreateBookingService;
use Carbon\Carbon;
use Exception;

class CalendarBookingAction
{
    public function __construct(
        public CreateBookingService $bookingService,
        public BookingAvailabilityService $bookingAvailabilityService,
        public BookingRuleChecker $bookingRuleChecker
    ) {}

    public function handle(Carbon $day, array $services): array
    {
        $services = $this->bookingService->getUsedServices($services);

        if ($services->isEmpty()) {
            throw new Exception('Vui lòng chọn ít nhất một dịch vụ');
        }

        $totalDuration = $services->sum('time_do');

        return $this->bookingAvailabilityService->getSlotAvailabilityWithSegments(
            $totalDuration,
            $day
        );
    }
}
