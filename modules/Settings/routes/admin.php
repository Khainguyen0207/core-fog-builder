<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Admin\Controllers\SettingController;

Route::middleware(['web', 'auth', 'ip.manager'])
    ->prefix('admin/settings')
    ->name('admin.settings.')
    ->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('store');
        Route::get('sepay', [SettingController::class, 'sePay'])->name('sepay');
        Route::get('work-time', [SettingController::class, 'workTime'])->name('work-time');
        Route::get('information-system', [SettingController::class, 'informationSystem'])->name('information-system');
        Route::get('telegram', [SettingController::class, 'telegram'])->name('telegram');
        Route::get('plugins', [SettingController::class, 'plugins'])->name('plugins');
        Route::post('plugins', [SettingController::class, 'updatePlugins'])->name('plugins.update');
    });
