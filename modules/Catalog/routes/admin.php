<?php

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Http\Admin\Controllers\CategoryController;
use Modules\Catalog\Http\Admin\Controllers\ServiceController;

Route::resource('admin/categories', CategoryController::class)
    ->middleware(['web', 'auth', 'ip.manager'])
    ->names('admin.categories');

Route::resource('admin/services', ServiceController::class)
    ->middleware(['web', 'auth', 'ip.manager'])
    ->names('admin.services');
