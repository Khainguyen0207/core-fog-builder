<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Admin\Controllers\UserController;

Route::resource('admin/users', UserController::class)
    ->middleware(['web', 'auth', 'ip.manager'])
    ->names('admin.users');
