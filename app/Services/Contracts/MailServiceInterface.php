<?php

namespace App\Services\Contracts;

interface MailServiceInterface
{
    public function send(array $payload): bool;
}
