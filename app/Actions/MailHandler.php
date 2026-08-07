<?php

namespace App\Actions;

use App\Services\MailService;

class MailHandler
{
    private string $template = 'email-templates.example';

    private const MAX_EMAILS_PER_FETCH = 5;

    protected MailService $service;

    public function __construct(MailService $service)
    {
        $this->service = $service;
    }

    public function handle(array $metadata): bool
    {
        if ($this->service->getTemplate() !== 'default') {
            $metadata['template'] = $this->service->getTemplate();

            return $this->service->sendWithTemplate($metadata);
        }

        return $this->service->send($metadata);
    }
}
