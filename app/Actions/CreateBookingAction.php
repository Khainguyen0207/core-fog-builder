<?php

namespace App\Actions;

use App\Enums\PaymentMethodEnum;
use App\Facades\SettingHelper;
use App\Jobs\SendBookingNotificationJob;
use App\Jobs\SendBookingToTelegramJob;
use App\Models\Booking;
use App\Services\BookingRuleChecker;
use App\Services\CouponEligibilityService;
use App\Services\CreateBookingService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateBookingAction
{
    public function __construct(
        private CreateBookingService $bookingService,
        private BookingRuleChecker $bookingRuleChecker,
        private CouponEligibilityService $couponEligibilityService
    ) {}

    public function handle(array $bookingValidated): Booking
    {
        DB::beginTransaction();

        try {
            [$services, $staffMapping] = $this->extractServiceData($bookingValidated);

            $this->validateServices($services);
            $this->validateWorkingTime($bookingValidated, $services);

            $booking = $this->bookingService->createBooking($bookingValidated, $services);

            $this->handlePayment($bookingValidated, $booking);
            $this->applyCouponIfPresent($bookingValidated, $booking);
            $this->bookingService->assignStaffToBookingService($services, $booking, $staffMapping);

            DB::commit();

            $booking = $booking->fresh()->loadMissing('bookingServices', 'bookingServices.staff');

            $isActiveTelegram = (bool) SettingHelper::get('is_active_telegram', false);

            if ($isActiveTelegram) {
                SendBookingToTelegramJob::dispatch($booking);
            }

            return $booking;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function applyCouponIfPresent(array $bookingValidated, Booking $booking): void
    {
        $couponCode = Arr::get($bookingValidated, 'coupon_code');

        if (empty($couponCode)) {
            return;
        }

        $user = Auth::guard('sanctum')->user();

        if (!$user || !$user->customer) {
            throw new Exception('Giảm giá chỉ áp dụng cho người dùng đã đăng nhập trên hệ thống.', 401);
        }

        $customerId = $user->customer->id;
        $price = (float) $booking->price;

        $coupon = $this->couponEligibilityService->validate($couponCode, $customerId, $price);

        $booking = app(ApplyCouponToBookingAction::class)->handle($booking, $coupon);

        app(CreateCouponRedemptionAction::class)->handle(
            $coupon,
            $customerId,
            (float) $booking->discount
        );
    }

    private function extractServiceData(array $bookingValidated): array
    {
        $serviceData = Arr::get($bookingValidated, 'services', []);
        $serviceIds = collect($serviceData)->pluck('service_id')->toArray();

        $staffMapping = collect($serviceData)->mapWithKeys(fn($item) => [
            $item['service_id'] => [
                'staff_id' => $item['staff_id'] ?? null,
                'start_time' => $item['start_time'] ?? null,
                'end_time' => $item['end_time'] ?? null,
            ]
        ])->toArray();

        $services = $this->bookingService->getUsedServices($serviceIds);

        return [$services, $staffMapping];
    }

    private function validateServices(Collection $services): void
    {
        if ($services->isEmpty()) {
            throw new Exception('Dịch vụ không tồn tại');
        }
    }

    private function validateWorkingTime(array $bookingValidated, Collection $services): void
    {
        [$scheduledStart, $estimatedEnd] = $this->bookingService
            ->calculateBookingDuration(Arr::get($bookingValidated, 'scheduled_start'), $services);

        if (!$this->bookingRuleChecker->isWorkingTime($scheduledStart, $estimatedEnd, $scheduledStart)) {
            throw new Exception('Đã hết thời gian làm việc');
        }

        if (!$this->bookingRuleChecker->canAcceptBooking($scheduledStart, $estimatedEnd)) {
            throw new Exception('Không có nhân viên trống trong thời gian đã chọn');
        }
    }

    private function handlePayment(array $bookingValidated, Booking $booking): void
    {
        if ($bookingValidated['payment_method'] !== PaymentMethodEnum::BANK_TRANSFER) {
            return;
        }

        $transaction = (new CreateTransactionAction(
            $booking->total_price,
            $booking->customer_name,
            $booking->customer_phone
        ))->handle();

        $booking->update(['transaction_code' => $transaction['transaction_code']]);
    }
}
