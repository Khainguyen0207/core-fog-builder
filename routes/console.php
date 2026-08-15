<?php

use App\Actions\SendBookingNotificationAction;
use App\Jobs\CheckTransactionJob;
use App\Jobs\HandleExpiredTransactionsJob;
use App\Plugins\PluginManager;
use Illuminate\Support\Facades\Schedule;

Schedule::timezone(config('app.timezone'));

Schedule::job(new CheckTransactionJob)
    ->everyTenSeconds()
    ->when(fn (): bool => app(PluginManager::class)->isEnabled('figure-admin/payments'));

Schedule::job(new HandleExpiredTransactionsJob)
    ->everyTenMinutes()
    ->when(fn (): bool => app(PluginManager::class)->isEnabled('figure-admin/payments'));

Schedule::call(fn () => app(SendBookingNotificationAction::class)->handle())
    ->dailyAt('9:30')
    ->when(fn (): bool => app(PluginManager::class)->isEnabled('figure-admin/communications'));
