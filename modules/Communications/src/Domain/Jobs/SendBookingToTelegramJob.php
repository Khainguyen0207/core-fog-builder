<?php

namespace App\Jobs;

use App\Actions\SendBookingTelegramAction;
use App\Models\Booking;
use App\Plugins\PluginManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendBookingToTelegramJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SendBookingTelegramAction $action, PluginManager $plugins): void
    {
        if (! $plugins->isEnabled('figure-admin/communications')) {
            return;
        }

        $action->handle($this->booking);
    }
}
