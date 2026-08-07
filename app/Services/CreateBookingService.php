<?php

namespace App\Services;

use App\Enums\BaseStatusEnum;
use App\Enums\BookingStatusEnum;
use App\Models\Booking;
use App\Models\BookingService as BookingServiceModel;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Staff;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateBookingService
{
    private const PREFIX = 'VSX';

    public function __construct(private BookingRuleChecker $bookingRuleChecker) {}

    public function createBooking(array $bookingRequest, Collection $services): Booking
    {
        [$scheduledStart, $estimatedEnd] = $this->calculateBookingDuration(
            Arr::get($bookingRequest, 'scheduled_start'),
            $services
        );

        $user = Auth::guard('sanctum')->user();

        $customer = $user?->customer;

        $originalPrice = $services->sum('price');

        $bookingData = array_merge([
            'customer_id' => $customer?->id,
            'booking_code' => $this->generateBookingCode(),
            'price' => $originalPrice,
            'discount' => 0,
            'total_price' => $originalPrice,
            'total_duration' => $services->sum('time_do'),
            'estimated_end' => $estimatedEnd,
            'scheduled_start' => $scheduledStart,
            'actual_start' => $scheduledStart,
            'status' => BookingStatusEnum::PENDING,
        ], Arr::except($bookingRequest, ['services', 'coupon_code']));

        return Booking::query()->create($bookingData);
    }

    public function getUsedServices(array $servicesIds): Collection
    {
        return Service::query()->whereIn('id', $servicesIds)->get();
    }

    public function calculateBookingDuration($scheduledStart, Collection $services): array
    {
        $scheduledStart = Carbon::parse($scheduledStart);
        $scheduledEnd = $scheduledStart->copy()->addMinutes($services->sum('time_do'));

        return [$scheduledStart, $scheduledEnd];
    }

    public function assignStaffToBookingService(Collection $services, Booking $booking, array $staffMapping = []): array
    {
        [$servicesWithTime, $servicesWithoutTime] = $this->separateServices($services, $staffMapping);
        $occupiedSlots = $this->collectOccupiedSlots($servicesWithTime);

        $bookingServices = $this->processServicesWithTime($servicesWithTime, $booking);
        $bookingServices = array_merge(
            $bookingServices,
            $this->processServicesWithoutTime($servicesWithoutTime, $booking, $occupiedSlots)
        );

        return $bookingServices;
    }

    private function separateServices(Collection $services, array $staffMapping): array
    {
        $servicesWithTime = [];
        $servicesWithoutTime = [];

        foreach ($services as $service) {
            if ($service->status !== BaseStatusEnum::ENABLED) {
                continue;
            }

            $serviceId = $service->getKey();
            $mapping = $staffMapping[$serviceId] ?? [];
            $hasTime = !empty($mapping['start_time']) && !empty($mapping['end_time']);

            $item = ['service' => $service, 'mapping' => $mapping];

            if ($hasTime) {
                $servicesWithTime[] = $item;
            } else {
                $servicesWithoutTime[] = $item;
            }
        }

        return [$servicesWithTime, $servicesWithoutTime];
    }

    private function collectOccupiedSlots(array $servicesWithTime): array
    {
        return collect($servicesWithTime)
            ->map(fn($item) => [
                'start' => Carbon::parse($item['mapping']['start_time']),
                'end' => Carbon::parse($item['mapping']['end_time']),
            ])
            ->sortBy('start')
            ->values()
            ->toArray();
    }

    private function processServicesWithTime(array $servicesWithTime, Booking $booking): array
    {
        $bookingServices = [];

        foreach ($servicesWithTime as $item) {
            $service = $item['service'];
            $mapping = $item['mapping'];

            $startAt = Carbon::parse($mapping['start_time']);
            $endAt = Carbon::parse($mapping['end_time']);
            $staffId = $this->resolveStaffId($mapping['staff_id'] ?? null, $startAt, $endAt, $service->title);

            $bookingServices[] = $this->createBookingService($booking, $service, $staffId, $startAt, $endAt);
        }

        return $bookingServices;
    }

    private function processServicesWithoutTime(array $servicesWithoutTime, Booking $booking, array $occupiedSlots): array
    {
        $bookingServices = [];
        $timeStart = $booking->scheduled_start;

        foreach ($servicesWithoutTime as $item) {
            $service = $item['service'];
            $mapping = $item['mapping'];
            $duration = $service->time_do;

            $startAt = $this->findNextAvailableSlot($timeStart, $duration, $occupiedSlots);
            $endAt = $startAt->copy()->addMinutes($duration);
            $staffId = $this->resolveStaffId($mapping['staff_id'] ?? null, $startAt, $endAt, $service->title);

            $bookingServices[] = $this->createBookingService($booking, $service, $staffId, $startAt, $endAt);
            $timeStart = $endAt;
        }

        return $bookingServices;
    }

    private function resolveStaffId(?int $specifiedStaffId, Carbon $startAt, Carbon $endAt, string $serviceName): int
    {
        if ($specifiedStaffId !== null) {
            return $this->validateAndGetSpecifiedStaff($specifiedStaffId, $startAt, $endAt, $serviceName);
        }

        return $this->getRandomAvailableStaff($startAt, $endAt, $serviceName);
    }

    private function createBookingService(Booking $booking, $service, int $staffId, Carbon $startAt, Carbon $endAt): BookingServiceModel
    {
        return BookingServiceModel::query()->create([
            'booking_id' => $booking->getKey(),
            'service_id' => $service->getKey(),
            'service_name' => $service->title,
            'price' => $service->price,
            'duration' => $service->time_do,
            'status' => BookingStatusEnum::CONFIRMED,
            'assigned_staff_id' => $staffId,
            'started_at' => $startAt,
            'finished_at' => $endAt,
        ]);
    }

    private function findNextAvailableSlot(Carbon $proposedStart, int $duration, array $occupiedSlots): Carbon
    {
        $startAt = $proposedStart->copy();
        $endAt = $startAt->copy()->addMinutes($duration);

        foreach ($occupiedSlots as $slot) {
            if ($startAt < $slot['end'] && $endAt > $slot['start']) {
                $startAt = $slot['end']->copy();
                $endAt = $startAt->copy()->addMinutes($duration);
            }
        }

        return $startAt;
    }

    private function validateAndGetSpecifiedStaff(int $staffId, Carbon $startAt, Carbon $endAt, string $serviceName): int
    {
        $staff = Staff::query()
            ->where('id', $staffId)
            ->where('is_active', true)
            ->first();

        if (! $staff) {
            throw new Exception("Nhân viên không tồn tại hoặc đã ngừng hoạt động.");
        }

        $isStaffBusy = BookingServiceModel::query()
            ->where('assigned_staff_id', $staffId)
            ->where('started_at', '<', $endAt)
            ->where('finished_at', '>', $startAt)
            ->whereNot('status', BookingStatusEnum::CANCELLED)
            ->exists();

        if ($isStaffBusy) {
            throw new Exception(
                "Nhân viên {$staff->name} đang bận không thể nhận dịch vụ \"{$serviceName}\" trong khoảng thời gian {$startAt->format('H:i')} - {$endAt->format('H:i')}. Vui lòng chọn nhân viên khác hoặc để hệ thống tự động chọn."
            );
        }

        if (!$this->bookingRuleChecker->canAssignStaffToBookingService($startAt, $endAt)) {
            throw new Exception(
                "Không thể đặt lịch cho dịch vụ \"{$serviceName}\" trong khoảng thời gian {$startAt->format('H:i')} - {$endAt->format('H:i')}. Thời gian nằm ngoài giờ làm việc."
            );
        }

        return $staffId;
    }

    private function getRandomAvailableStaff(Carbon $startAt, Carbon $endAt, string $serviceName): int
    {
        $availableStaffIds = $this->getStaffAvailableAtTime($startAt, $endAt)->pluck('id');

        if ($availableStaffIds->isEmpty() || !$this->bookingRuleChecker->canAssignStaffToBookingService($startAt, $endAt)) {
            throw new Exception(
                "Không tìm thấy nhân viên trống cho dịch vụ \"{$serviceName}\" trong khoảng thời gian {$startAt->format('H:i')} - {$endAt->format('H:i')}."
            );
        }

        return $availableStaffIds->random();
    }

    private function getStaffAvailableAtTime(Carbon $startAt, Carbon $endAt): Collection
    {
        return Staff::query()
            ->whereDoesntHave(
                'bookingServices',
                fn($q) => $q
                    ->where('started_at', '<', $endAt)
                    ->where('finished_at', '>', $startAt)
                    ->where('status', '!=', BookingStatusEnum::CANCELLED)
            )
            ->where('is_active', true)
            ->lockForUpdate()
            ->get();
    }

    private function generateBookingCode(): string
    {
        do {
            $code = self::PREFIX . Str::upper(Str::random(10));
        } while (Booking::query()->where('booking_code', $code)->exists());

        return $code;
    }
}
