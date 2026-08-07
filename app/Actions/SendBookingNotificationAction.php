<?php

namespace App\Actions;

use App\Enums\BookingStatusEnum;
use App\Jobs\SendBookingNotificationJob;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBookingNotificationAction
{
    private const SUPPORT_HOTLINE = '1900-0000';

    public function handle(): void
    {
        $timezone = config('app.timezone');

        $now = Carbon::now();
        $targetDate = $now->copy()->addDay();

        Booking::query()
            ->where('status', BookingStatusEnum::CONFIRMED)
            ->whereNotNull('notify_email')
            ->where('notify_email', '!=', '')
            ->whereBetween('scheduled_start', [$now->startOfDay(), $targetDate->endOfDay()])
            ->orderBy('id')
            ->chunkById(200, function ($bookings) {
                foreach ($bookings as $booking) {
                    $this->dispatchNotification($booking);
                }
            });
    }

    private function dispatchNotification(Booking $booking): void
    {
        try {
            $scheduledStart = Carbon::parse($booking->scheduled_start);

            $subject = sprintf(
                'Nhắc lịch hẹn %s - %s Mr/Mrs %s',
                $booking->booking_code,
                $scheduledStart->format('d/m/Y H:i'),
                $booking->customer_name,
            );

            $html = view('admin.templates.email-templates.booking-notification', [
                'booking' => $booking,
                'appName' => config('app.name'),
                'support' => self::SUPPORT_HOTLINE,
            ])->render();

            SendBookingNotificationJob::dispatch(
                email: $booking->notify_email,
                subject: $subject,
                htmlContent: $html
            );

            Log::info('Booking notification dispatched', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'notify_email' => $booking->notify_email,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to dispatch booking notification', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'notify_email' => $booking->notify_email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
