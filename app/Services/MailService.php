<?php

namespace App\Services;

use App\Services\Contracts\MailServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

abstract class MailService implements MailServiceInterface
{
    private const DYNAMIC = 'dynamic';

    protected const DYNAMIC_MAILER_CONFIG = 'mail.mailers.dynamic';

    protected const KEY_CACHE_MAILER = 'log_mailer_';

    protected string $template;

    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function applyConfig(array $config): void
    {
        config([static::DYNAMIC_MAILER_CONFIG => $config]);
    }

    public function send(array $payload): bool
    {
        try {
            if (empty($this->config)) {
                throw new \Exception('Configure Mailer failed');
            }

            $this->applyConfig($this->config);

            Mail::mailer(self::DYNAMIC)->raw(Arr::get($payload, 'message'), function ($mailer) use ($payload) {
                $from = Arr::get($payload, 'from');
                $title = Arr::get($payload, 'title');
                $to = Arr::get($payload, 'to');
                $subject = Arr::get($payload, 'subject');

                $mailer->from($from, $title)
                    ->to($to)
                    ->subject($subject);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Mailer send message failed: '.$e->getMessage());

            return false;
        }
    }

    public function sendWithTemplate(array $payload): bool
    {
        try {
            if (empty($this->config)) {
                throw new \Exception('Configure Mailer failed');
            }

            $this->applyConfig($this->config);

            $view = view($payload['template'], [
                'otp' => $payload['message'],
                'appName' => config('app.name'),
                'support' => config('mail.support'),
            ])->render();

            Mail::mailer(self::DYNAMIC)->html($view, function ($mailer) use ($payload) {
                $from = Arr::get($payload, 'from');
                $title = Arr::get($payload, 'title');
                $to = Arr::get($payload, 'to');
                $subject = Arr::get($payload, 'subject');

                $mailer->from($from, $title)
                    ->to($to)
                    ->subject($subject);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Mailer send message failed: '.$e->getMessage());

            return false;
        } catch (\Throwable $e) {
            Log::error('Mailer send message failed: '.$e->getMessage());

            return false;
        }
    }

    private function logMailerSendCount(array $payload): void
    {
        $key = self::KEY_CACHE_MAILER.$payload['mailer_id'];
        $seconds = now()->secondsUntilEndOfDay();

        Cache::add($key, 0, $seconds);
        Cache::increment($key);

        Str::random(60);
    }

    public static function getMailerLogs(string $mailerId): int
    {
        $key = self::KEY_CACHE_MAILER.$mailerId;

        return Cache::get($key) ?? 0;
    }

    public function setTemplate(string $template): void
    {
        $templates = config('mail.templates');

        if (in_array($template, $templates)) {
            $this->template = $template;
        } else {
            $this->template = 'default';
        }
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}
