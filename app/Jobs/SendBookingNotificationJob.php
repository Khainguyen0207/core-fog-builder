<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $email,
        public string $subject,
        public string $htmlContent
    ) {}

    public function handle(): void
    {
        if (str_contains($this->email, '@example.com')) {
            return;
        }

        try {
            Mail::html($this->htmlContent, function ($message) {
                $message->to($this->email)
                    ->subject($this->subject);
            });

            Log::info("Sent booking notification email to: {$this->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send booking notification email to {$this->email}: " . $e->getMessage());
            throw $e;
        }
    }
}
