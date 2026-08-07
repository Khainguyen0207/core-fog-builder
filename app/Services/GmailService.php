<?php

namespace App\Services;

class GmailService extends MailService
{
    public function setupSendOTP(): static
    {
        $this->setTemplate('otp-template');

        return $this;
    }
}
