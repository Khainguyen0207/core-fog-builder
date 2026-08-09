<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Admin\Controllers\DashboardController;

Route::middleware(['web', 'auth', 'ip.manager'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('dashboard', DashboardController::class);
    });
