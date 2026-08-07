<?php

namespace App\Jobs;

use App\Actions\SendBookingTelegramAction;
use App\Models\Booking;
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
    public function handle(): void
    {
        app(SendBookingTelegramAction::class)->handle($this->booking);
    }
}
