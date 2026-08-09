<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminUi\Http\Controllers\DataTableController;

Route::post('admin/get-data/{table}', DataTableController::class)
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.get-data');
