<?php

namespace App\Actions;

use App\Facades\SettingHelper;
use App\Services\SePayPaymentService;
use App\Services\TransactionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckTransactionAction
{
    public function __construct(
        private TransactionService $transactionService
    ) {}

    public function handle()
    {
        DB::transaction(function () {
            $provider = $this->getProviderFromSetting();

            if ($provider === null) {
                return;
            }

            $transactions = $this->transactionService->getTransactionsAwaitingProcessing();

            if (count($transactions) == 0) {
                Log::info('No transactions found');
                return;
            }

            Log::info('Transactions found: ' . count($transactions));

            $start = $transactions->first()->created_at;

            $partnerTransactions = $provider->getTransactionAmountInByDate($start);
            Log::info('Partner transactions found: ' . json_encode($partnerTransactions));
            $partnerTransactions = Arr::get($partnerTransactions, 'transactions', []);

            Log::info('Partner transactions found: ' . count($partnerTransactions));

            $matchedData = $this->transactionService->comparePartnerTransaction($partnerTransactions, $transactions);

            $this->transactionService->updateTransactionStatus($matchedData['matchedIds'], $matchedData['responseById']);
        });
    }

    public function getProviderFromSetting(): mixed
    {
        $provider = SettingHelper::get('payment_provider');

        if (empty($provider)) {
            return null;
        }

        return $this->getProviderService($provider);
    }

    public function getProviderService(string $provider): mixed
    {
        return match ($provider) {
            'sepay' => new SePayPaymentService,
            default => null,
        };
    }
}
