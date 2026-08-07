<?php

namespace App\Services;

class MailConfigResolver
{
    public static function resolve(): array
    {
        return config('mail.mailers.smtp');
    }
}
