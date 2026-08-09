<?php

use Illuminate\Support\Facades\Route;
use Modules\Payments\Http\Admin\Controllers\TransactionController;

Route::get('admin/transactions', [TransactionController::class, 'index'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.transactions.index');

Route::get('admin/transactions/{transaction}', [TransactionController::class, 'show'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.transactions.show');

Route::put('admin/transactions/{transaction}', [TransactionController::class, 'update'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.transactions.update');
