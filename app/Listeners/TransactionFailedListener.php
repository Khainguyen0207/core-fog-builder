<?php

namespace App\Listeners;

use App\Enums\PaymentMethodEnum;
use App\Events\TransactionFailedEvent;
use App\Plugins\PluginManager;
use Illuminate\Support\Facades\Log;

class TransactionFailedListener
{
    public function __construct(private readonly PluginManager $plugins) {}

    public function handle(TransactionFailedEvent $event): void
    {
        if (! $this->plugins->isEnabled('figure-admin/payments')) {
            return;
        }

        $transaction = $event->transaction;

        $transaction->update([
            'payment_method' => PaymentMethodEnum::PAY_LATER,
        ]);

        Log::info("Transaction #{$transaction->id} payment_method changed to pay_later");

        $booking = $transaction->booking;

        if ($booking) {
            $booking->update([
                'payment_method' => PaymentMethodEnum::PAY_LATER,
            ]);

            Log::info("Booking #{$booking->id} payment_method changed to pay_later");
        }
    }
}
