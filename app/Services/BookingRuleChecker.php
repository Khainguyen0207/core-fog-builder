<?php

namespace App\Services;

use App\Enums\BaseStatusEnum;
use App\Enums\BookingStatusEnum;
use App\Facades\SettingHelper;
use App\Models\Booking;
use App\Models\BookingService as BookingServiceModel;
use App\Models\Staff;
use Carbon\Carbon;

class BookingRuleChecker
{
    public function canAcceptBooking(Carbon $scheduledStart, Carbon $estimated_end): bool
    {
        $booking = Booking::query()
            ->where('scheduled_start', '<', $estimated_end)
            ->where('estimated_end', '>', $scheduledStart)
            ->whereNot('status', BookingStatusEnum::CANCELLED)
            ->lockForUpdate()
            ->count();

        return $booking < $this->getMaxActiveStaff();
    }

    public function canAssignStaffToBookingService($startAt, $finishedAt): bool
    {
        $staffCount = Staff::query()
            ->where('is_active', true)
            ->count();

        if ($staffCount === 0) {
            return false;
        }

        $canAssignStaff = BookingServiceModel::query()
            ->where('started_at', '<', $startAt)
            ->where('finished_at', '>', $finishedAt)
            ->whereNot('status', BookingStatusEnum::CANCELLED)
            ->lockForUpdate()
            ->count();

        return $canAssignStaff < $this->getMaxActiveStaff();
    }

    public function isWorkingTime(Carbon $from, Carbon $to, Carbon $day): bool
    {
        $timeWork = get_work_schedule_by_date($day);

        if ($timeWork == null) {
            return false;
        }

        [$timeWorkStart, $timeWorkEnd] = $timeWork;

        $timeWorkStart = Carbon::parse($timeWorkStart)
            ->setDate($from->year, $from->month, $from->day);
        $timeWorkEnd = Carbon::parse($timeWorkEnd)
            ->setDate($to->year, $to->month, $to->day);

        if ($from->lt($timeWorkStart) || $from->gt($timeWorkEnd)) {
            return false;
        }

        if ($to->lt($timeWorkStart) || $to->gt($timeWorkEnd)) {
            return false;
        }

        return true;
    }

    private function getMaxActiveStaff(): int
    {
        return SettingHelper::get('max_active_staff') ?? 0;
    }
}
