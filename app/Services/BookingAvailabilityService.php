<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BookingStatusEnum;
use App\Facades\SettingHelper;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BookingAvailabilityService
{
    public function __construct(public BookingRuleChecker $bookingRuleChecker) {}

    public function getBookingByDuration(Carbon $timeStart, Carbon $timeEnd): Collection
    {
        return Booking::query()
            ->where('scheduled_start', '<', $timeEnd)
            ->where('estimated_end', '>', $timeStart)
            ->whereNot('status', BookingStatusEnum::CANCELLED)
            ->orderBy('scheduled_start')
            ->orderBy('estimated_end')
            ->get(['scheduled_start', 'estimated_end']);
    }

    public function getSlotAvailabilityWithSegments(
        int $slotDuration,
        Carbon $day,
        ?int $capacity = null,
        bool $includeHourMarks = true
    ): array {
        $capacity ??= (int) SettingHelper::get('max_active_staff', 2);

        [$windowStart, $windowEnd] = $this->resolveWorkingWindow($day);

        if ($windowStart->greaterThanOrEqualTo($windowEnd)) {
            return [];
        }

        $bookings = $this->getBookingByDuration($windowStart, $windowEnd);
        $segments = $this->buildConcurrencySegments($bookings, $windowStart, $windowEnd, $capacity);

        $candidates = $this->generateCandidateStartTimes($windowStart, $windowEnd, $slotDuration, $segments, $includeHourMarks);

        return $this->evaluateCandidates($candidates, $segments, $slotDuration, $windowEnd, $capacity);
    }

    private function resolveWorkingWindow(Carbon $day): array
    {
        $workSchedule = get_work_schedule_by_date($day);
        if ($workSchedule === null) {
            return [$day->copy(), $day->copy()];
        }

        [$workStartStr, $workEndStr] = $workSchedule;
        $windowStart = $day->copy()->setTimeFromTimeString($workStartStr);
        $windowEnd = $day->copy()->setTimeFromTimeString($workEndStr);

        if ($day->isToday()) {
            $nextHour = Carbon::now()->addHour()->startOfHour();
            if ($nextHour->gt($windowStart)) {
                $windowStart = $nextHour;
            }
        }

        return [$windowStart, $windowEnd];
    }

    private function buildConcurrencySegments(
        Collection $bookings,
        Carbon $windowStart,
        Carbon $windowEnd,
        int $capacity
    ): array {
        $events = $this->buildCapacityEvents($bookings, $windowStart, $windowEnd);

        $segments = [];
        $current = 0;
        $segmentStart = $windowStart->copy();

        foreach ($events as $event) {
            $segmentEnd = $event['time'];

            if ($segmentStart->lt($segmentEnd)) {
                $segments[] = [
                    'start' => $segmentStart->copy(),
                    'end' => $segmentEnd->copy(),
                    'concurrency' => $current,
                    'remainingSlots' => $capacity - $current,
                ];
            }

            $current += $event['delta'];
            $segmentStart = $segmentEnd->copy();
        }

        if ($segmentStart->lt($windowEnd)) {
            $segments[] = [
                'start' => $segmentStart->copy(),
                'end' => $windowEnd->copy(),
                'concurrency' => $current,
                'remainingSlots' => $capacity - $current,
            ];
        }

        return $segments;
    }

    private function buildCapacityEvents(Collection $bookings, Carbon $windowStart, Carbon $windowEnd): array
    {
        $events = [];

        foreach ($bookings as $booking) {
            $bookingStart = Carbon::parse($booking->scheduled_start);
            $bookingEnd = Carbon::parse($booking->estimated_end);

            if ($bookingStart->gte($windowEnd) || $bookingEnd->lte($windowStart)) {
                continue;
            }

            $clampedStart = $bookingStart->copy()->max($windowStart);
            $clampedEnd = $bookingEnd->copy()->min($windowEnd);

            if ($clampedStart->lt($clampedEnd)) {
                $events[] = ['time' => $clampedStart, 'delta' => +1];
                $events[] = ['time' => $clampedEnd, 'delta' => -1];
            }
        }

        usort(
            $events,
            fn($a, $b) =>
            $a['time']->timestamp <=> $b['time']->timestamp
                ?: $a['delta'] <=> $b['delta']
        );

        return $events;
    }

    private function generateCandidateStartTimes(
        Carbon $windowStart,
        Carbon $windowEnd,
        int $slotDuration,
        array $segments,
        bool $includeHourMarks
    ): array {
        $candidates = [];

        $current = $windowStart->copy();
        while ($current->copy()->addMinutes($slotDuration)->lte($windowEnd)) {
            $candidates[$current->timestamp] = $current->copy();
            $current->addMinutes($slotDuration);
        }

        if ($includeHourMarks) {
            $hourMark = $windowStart->copy()->startOfHour();
            if ($hourMark->lt($windowStart)) {
                $hourMark->addHour();
            }
            while ($hourMark->copy()->addMinutes($slotDuration)->lte($windowEnd)) {
                $candidates[$hourMark->timestamp] = $hourMark->copy();
                $hourMark->addHour();
            }
        }

        foreach ($segments as $segment) {
            $boundary = $segment['start'];
            if ($boundary->gte($windowStart) && $boundary->copy()->addMinutes($slotDuration)->lte($windowEnd)) {
                $candidates[$boundary->timestamp] = $boundary->copy();
            }
        }

        ksort($candidates);
        return array_values($candidates);
    }

    private function evaluateCandidates(
        array $candidates,
        array $segments,
        int $slotDuration,
        Carbon $windowEnd,
        int $capacity
    ): array {
        $slots = [];

        foreach ($candidates as $slotStart) {
            $slotEnd = $slotStart->copy()->addMinutes($slotDuration);

            if ($slotEnd->gt($windowEnd)) {
                continue;
            }

            $maxConcurrency = $this->getMaxConcurrencyForSlot($segments, $slotStart, $slotEnd);
            $remainingSlots = $capacity - $maxConcurrency;
            $isAvailable = $remainingSlots > 0;

            $slot = [
                'start' => $slotStart->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'available' => $isAvailable,
                'remainingSlots' => $remainingSlots,
                'maxSlots' => $capacity,
                'color' => $isAvailable ? (ceil($capacity / 2) >= $remainingSlots ? 'warning' : 'success') : 'secondary',
            ];

            if (!$isAvailable) {
                $jumpTo = $this->findNextAvailableBoundary($segments, $slotEnd, $windowEnd);
                if ($jumpTo !== null) {
                    $slot['jumpTo'] = $jumpTo->format('H:i');
                }
            }

            $slots[] = $slot;
        }

        return $slots;
    }

    private function getMaxConcurrencyForSlot(array $segments, Carbon $slotStart, Carbon $slotEnd): int
    {
        $maxConcurrency = 0;

        foreach ($segments as $segment) {
            if ($segment['end']->lte($slotStart) || $segment['start']->gte($slotEnd)) {
                continue;
            }

            if ($segment['concurrency'] > $maxConcurrency) {
                $maxConcurrency = $segment['concurrency'];
            }
        }

        return $maxConcurrency;
    }

    private function findNextAvailableBoundary(array $segments, Carbon $after, Carbon $windowEnd): ?Carbon
    {
        foreach ($segments as $segment) {
            if ($segment['start']->gte($after) && $segment['remainingSlots'] > 0) {
                return $segment['start']->copy();
            }
        }

        return null;
    }
}
