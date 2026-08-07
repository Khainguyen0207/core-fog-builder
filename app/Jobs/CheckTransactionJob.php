<?php

namespace App\Jobs;

use App\Actions\CheckTransactionAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckTransactionJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct() {}

    public function handle(CheckTransactionAction $action): void
    {
        $action->handle();
    }
}
