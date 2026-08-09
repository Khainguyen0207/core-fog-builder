<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Admin\Controllers\AuthenticationController;

Route::middleware(['web', 'guest'])->group(function () {
    Route::get('login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('login', [AuthenticationController::class, 'authenticate'])->name('login.authenticate');
});

Route::get('admin/logout', [AuthenticationController::class, 'logout'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.logout');
