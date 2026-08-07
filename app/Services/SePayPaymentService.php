<?php

namespace App\Services;

use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentProviderEnum;
use App\Enums\TransactionStatusEnum;
use App\Facades\SettingHelper;
use App\Models\Transaction;
use App\Services\Contracts\PaymentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SePayPaymentService implements PaymentService
{
    protected string $baseUrl = 'https://my.sepay.vn/userapi/transactions';

    public function getTransactionAmountInByDate(string $start = '', string $end = ''): ?array
    {
        try {
            $endpoint = $this->baseUrl . '/list?amount_out=0';

            if ($start !== '') {
                $start = Carbon::parse($start)->setTime(0, 0, 0)->format('Y-m-d H:i:s');
                $endpoint = $this->baseUrl . '/list?amount_out=0&transaction_date_min=' . $start;
            }

            if ($end !== '') {
                $end = Carbon::parse($end)->setTime(23, 59, 59)->format('Y-m-d H:i:s');
                $endpoint = $this->baseUrl . '/list?amount_out=0&transaction_date_max=' . $end;
            }

            if (config('payment.sepay.api_token') == '') {
                throw new Exception("SePay API token is not set");
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('payment.sepay.api_token'),
            ])->get($endpoint);

            if (! $response->ok()) {
                Log::error('API SePay list transaction error');
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('API SePay connection error: ' . $e->getMessage());

            return null;
        }
    }

    public function generateQRCode(int $amount, string $transactionCode, string $accountName): string
    {
        $metaData = [
            'amount' => $amount,
            'addInfo' => $transactionCode,
            'accountName' => $accountName,
        ];

        $config = $this->getPaymentConfig();

        return VietQR::generateQRCode($metaData, $config['bank_name'], $config['account_number']);
    }

    public function getPaymentConfig(): array
    {
        return [
            'bank_name' => SettingHelper::get('bank_name'),
            'account_number' => SettingHelper::get('account_number'),
            'receiver_name' => SettingHelper::get('receiver_name'),
        ];
    }

    public function createTransaction(int $amount, string $code, string $customerName, string $customerPhone)
    {
        $config = $this->getPaymentConfig();

        $transaction = [
            'token' => Str::random(40),
            'amount' => $amount,
            'receiver_name' => $config['receiver_name'],
            'provider_code' => PaymentProviderEnum::SEPAY,
            'bank_name' => $config['bank_name'],
            'account_number' => $config['account_number'],
            'status' => TransactionStatusEnum::PENDING,
            'expired_at' => now()->addMinutes(10),
            'transaction_code' => Str::upper($code),
            'customer_phone' => $customerPhone,
            'customer_name' => $customerName,
            'payment_method' => PaymentMethodEnum::BANK_TRANSFER,
        ];

        $transaction = Transaction::query()->create($transaction);

        return array_merge($transaction->toArray(), [
            'qr_url' => $this->generateQRCode($amount, $code, $config['receiver_name']),
        ]);
    }
}
