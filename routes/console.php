<?php

use App\Actions\SendBookingNotificationAction;
use App\Jobs\CheckTransactionJob;
use App\Jobs\HandleExpiredTransactionsJob;
use Illuminate\Support\Facades\Schedule;

Schedule::timezone(config('app.timezone'));

Schedule::job(new CheckTransactionJob)->everyTenSeconds();

Schedule::job(new HandleExpiredTransactionsJob)->everyTenMinutes();

Schedule::call(fn() => app(SendBookingNotificationAction::class)->handle())
    ->dailyAt('9:30');
