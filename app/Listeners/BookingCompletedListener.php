<?php

namespace App\Listeners;

use App\Actions\UpdateMembershipLevelAction;
use App\Enums\BookingStatusEnum;
use App\Events\BookingStatusChangedEvent;

class BookingCompletedListener
{
    public function handle(BookingStatusChangedEvent $event): void
    {
        $booking = $event->booking;
        $previousStatus = $booking->getOriginal('status')->getValue();
        $bookingStatus = $booking->status->getValue();

        if (
            $bookingStatus === BookingStatusEnum::DONE
            && $previousStatus !== BookingStatusEnum::DONE
        ) {
            $customer = $booking->customer;

            if ($customer) {
                $customer->total_spent += $booking->total_price;
                $customer->save();

                app(UpdateMembershipLevelAction::class)->handle($customer);
            }
        }
    }
}
