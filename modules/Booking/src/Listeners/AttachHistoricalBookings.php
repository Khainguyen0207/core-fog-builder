<?php

namespace Modules\Booking\Listeners;

use App\Enums\BookingStatusEnum;
use App\Events\CustomerCreatedEvent;
use App\Models\Booking;
use App\Plugins\PluginManager;

class AttachHistoricalBookings
{
    public function __construct(private readonly PluginManager $plugins) {}

    public function handle(CustomerCreatedEvent $event): void
    {
        if (! $this->plugins->isEnabled('figure-admin/booking')) {
            return;
        }

        $customer = $event->customer;

        Booking::query()
            ->where('customer_phone', $customer->phone)
            ->whereNull('customer_id')
            ->update(['customer_id' => $customer->id]);

        $totalSpent = Booking::query()
            ->where('customer_phone', $customer->phone)
            ->where('status', BookingStatusEnum::DONE)
            ->sum('total_price');

        $customer->update(['total_spent' => $totalSpent]);
    }
}
