<?php

namespace App\Jobs;

use App\Enums\TransactionStatusEnum;
use App\Events\TransactionFailedEvent;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class HandleExpiredTransactionsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct() {}

    public function handle(): void
    {
        $now = Carbon::now();

        Transaction::query()
            ->where('status', TransactionStatusEnum::PENDING)
            ->where('expired_at', '<', $now)
            ->chunkById(100, function ($transactions) use ($now) {
                foreach ($transactions as $transaction) {
                    $hoursExpired = $transaction->expired_at->diffInHours($now);

                    if ($hoursExpired >= 24) {
                        $transaction->update([
                            'status' => TransactionStatusEnum::FAILED,
                        ]);

                        Log::info("Transaction #{$transaction->id} marked as FAILED (expired {$hoursExpired}h ago)");

                        event(new TransactionFailedEvent($transaction));
                    } else {
                        $transaction->update([
                            'status' => TransactionStatusEnum::EXPIRED,
                        ]);

                        Log::info("Transaction #{$transaction->id} marked as EXPIRED (expired {$hoursExpired}h ago)");
                    }
                }
            });
    }
}
