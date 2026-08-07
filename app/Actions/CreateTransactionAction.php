<?php

namespace App\Actions;

use App\Facades\SettingHelper;
use App\Models\Transaction;
use App\Services\SePayPaymentService;
use Illuminate\Support\Str;

class CreateTransactionAction
{
    public function __construct(
        public int $amount,
        public string $customerName,
        public string $customerPhone,
    ) {}

    public function handle()
    {
        $provider = $this->getProviderFromSetting();

        $code = $this->generateTokenTransaction();

        $transaction = $provider->createTransaction($this->amount, $code, $this->customerName, $this->customerPhone);

        return $transaction;
    }

    public function generateTokenTransaction(): string
    {
        do {
            $code = Str::random(10);
            $exist = Transaction::query()->where('transaction_code', $code)->exists();
        } while ($exist);

        return $code;
    }

    public function getProviderFromSetting()
    {
        $provider = SettingHelper::get('payment_provider');

        switch ($provider) {
            case 'sepay':
                return new SePayPaymentService;
            default:
                throw new \Exception('Provider not found, please config in System Settings');
        }
    }
}
