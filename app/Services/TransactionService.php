<?php

namespace App\Services;

use App\Enums\BookingStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function getTransactionsAwaitingProcessing()
    {
        $transactions = Transaction::query()
            ->whereIn('status', [TransactionStatusEnum::PENDING, TransactionStatusEnum::EXPIRED])
            ->orderBy('created_at')
            ->lockForUpdate()
            ->limit(100)
            ->get();

        if ($transactions->count() == 0) {
            return [];
        }

        return $transactions;
    }

    public function updateTransactionStatus(array $matchedIds, array $responseById)
    {
        foreach (array_chunk($matchedIds, 200) as $chunk) {
            $rows = Transaction::query()
                ->with('booking')
                ->whereIn('id', $chunk)
                ->get();

            foreach ($rows as $row) {
                if (isset($responseById[$row->id])) {
                    $row->update([
                        'status' => TransactionStatusEnum::COMPLETED,
                        'response' => $responseById[$row->id],
                    ]);

                    $row?->booking->update([
                        'status' => BookingStatusEnum::CONFIRMED,
                    ]);
                }
            }
        }
    }

    public function comparePartnerTransaction(array $partnerTransactions, Collection $transactions)
    {
        $localIndex = [];

        foreach ($transactions as $transaction) {
            $amount = $transaction->amount;
            $codeLower = mb_strtolower($transaction->transaction_code);

            $localIndex[$amount][$codeLower] = [
                'id' => $transaction->id,
                'code' => $transaction->transaction_code,
            ];
        }

        $matchedIds = [];
        $responseById = [];

        foreach ($partnerTransactions as $transaction) {
            $amount = floatval($transaction['amount_in']);

            if ($amount === '') {
                continue;
            }

            $contentRaw = $transaction['transaction_content'];
            if ($contentRaw === '') {
                continue;
            }

            $content = mb_strtolower($contentRaw);
            $candidates = Arr::get($localIndex, (int) $amount);

            if (! $candidates) {
                continue;
            }

            foreach ($candidates as $codeLower => $meta) {
                if (str_contains($content, $codeLower)) {
                    $id = $meta['id'];

                    $matchedIds[] = $id;

                    $responseById[$id] = json_encode($transaction, JSON_UNESCAPED_UNICODE);

                    break;
                }
            }
        }

        return [
            'matchedIds' => $matchedIds,
            'responseById' => $responseById,
        ];
    }
}
