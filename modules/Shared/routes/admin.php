<?php

use Illuminate\Support\Facades\Route;
use Modules\Shared\Http\Controllers\BulkDeleteController;
use Modules\Shared\Http\Controllers\DataTableController;

Route::middleware(config('figure-admin-shared.routes.middleware', ['web', 'auth']))
    ->prefix(config('figure-admin-shared.routes.prefix', 'admin'))
    ->name(config('figure-admin-shared.routes.name_prefix', 'admin.'))
    ->group(function (): void {
        Route::post('get-data/{table}', DataTableController::class)->name('get-data');
        Route::post('bulk-delete', BulkDeleteController::class)->name('bulk-delete');
    });
