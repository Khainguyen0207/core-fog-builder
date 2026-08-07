<?php

namespace App\Services\Contracts;

interface PaymentService
{
    public function getTransactionAmountInByDate(string $start = '', string $end = ''): ?array;

    public function createTransaction(int $amount, string $code, string $customerName, string $customerPhone);

    public function getPaymentConfig(): array;
}
