<?php

namespace App\Jobs;

use App\Actions\MailHandler;
use App\Services\GmailService;
use App\Services\MailConfigResolver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class MailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        public string $from,
        public string $to,
        public string $subject,
        public string $message,
        public string $title,
        public string $template = 'default',
        public bool $isSystem = false,
    ) {}

    public function handle(): void
    {
        $mailConfig = MailConfigResolver::resolve();

        $metadata = [
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            'title' => $this->title,
            'message' => $this->message,
        ];

        if (empty($mailConfig)) {
            return;
        }

        try {
            Log::info('Start Send Mail...');

            $gmailService = new GmailService($mailConfig);
            $gmailService->setTemplate($this->template);

            $handler = new MailHandler($gmailService);
            $handler->handle($metadata);

            Log::info('Send Mail completed...');
        } catch (\Exception $exception) {
            Log::error('Send Mail failed: ' . $exception->getMessage());
        }
    }
}
