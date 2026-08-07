<?php

namespace App\Jobs;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendTemplateEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(
        public array $userIds,
        public int $templateId
    ) {}

    public function handle(): void
    {
        $template = EmailTemplate::find($this->templateId);

        if (! $template) {
            Log::error("SendTemplateEmailJob: Template #{$this->templateId} not found");

            return;
        }

        $htmlContent = $this->resolveHtmlContent($template);

        if (empty($htmlContent)) {
            Log::error("SendTemplateEmailJob: No HTML content for template #{$template->id}");

            return;
        }

        $users = User::with('customer')
            ->whereIn('id', $this->userIds)
            ->whereNotNull('email')
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            if (str_contains($user->email, '@example.com')) {
                continue;
            }

            $variables = [
                'customer_name' => $user->customer?->name ?? $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->customer?->phone ?? '',
                'membership_code' => $user->customer?->membership_code
                    ? $user->customer->membership_code->getLabel()
                    : 'DEFAULT',
            ];

            $renderedHtml = $this->replaceVariables($htmlContent, $variables);

            try {
                Mail::html($renderedHtml, function ($message) use ($user, $template) {
                    $message->to($user->email)
                        ->subject($template->name);
                });
            } catch (\Exception $e) {
                Log::error("SendTemplateEmailJob: Failed for {$user->email} — {$e->getMessage()}");
            }
        }

        Log::info("SendTemplateEmailJob: Sent {$users->count()} email(s) using [{$template->name}]");
    }

    private function resolveHtmlContent(EmailTemplate $template): ?string
    {
        if (! empty($template->file_path) && Storage::exists($template->file_path)) {
            return Storage::get($template->file_path);
        }

        return $template->html_content;
    }

    private function replaceVariables(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $content = preg_replace(
                '/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/',
                (string) $value,
                $content
            ) ?? $content;
        }

        return $content;
    }
}
