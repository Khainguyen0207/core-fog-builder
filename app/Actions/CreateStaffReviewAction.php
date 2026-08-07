<?php

namespace App\Actions;

use App\Enums\BookingStatusEnum;
use App\Models\BookingService;
use App\Models\StaffReview;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateStaffReviewAction
{
    public function handle(array $validated, int $customerId): StaffReview
    {
        DB::beginTransaction();

        try {
            $bookingService = BookingService::with('booking')
                ->findOrFail($validated['booking_service_id']);

            if ($bookingService->booking->status->getValue() !== BookingStatusEnum::DONE) {
                throw new Exception('Chỉ có thể đánh giá sau khi dịch vụ hoàn tất.');
            }

            if ($bookingService->staffReview()->exists()) {
                throw new Exception('Dịch vụ này đã được đánh giá.');
            }

            $review = StaffReview::create([
                'customer_id' => $customerId,
                'staff_id' => $bookingService->assigned_staff_id,
                'booking_service_id' => $bookingService->id,
                'rating' => $validated['rating'],
                'note' => $validated['note'] ?? null,
            ]);

            //Action Update rate đánh giá

            DB::commit();

            return $review->load(['customer', 'staff', 'bookingService']);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
