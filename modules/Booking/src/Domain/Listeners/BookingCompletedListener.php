<?php

namespace App\Listeners;

use App\Actions\UpdateMembershipLevelAction;
use App\Enums\BookingStatusEnum;
use App\Events\BookingStatusChangedEvent;
use App\Plugins\PluginManager;

class BookingCompletedListener
{
    public function __construct(private readonly PluginManager $plugins) {}

    public function handle(BookingStatusChangedEvent $event): void
    {
        if (! $this->plugins->isEnabled('figure-admin/booking')) {
            return;
        }

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
