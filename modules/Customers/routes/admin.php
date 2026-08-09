<?php

use Illuminate\Support\Facades\Route;
use Modules\Customers\Http\Admin\Controllers\CustomerController;
use Modules\Customers\Http\Admin\Controllers\MembershipSettingController;

Route::resource('admin/customers', CustomerController::class)
    ->middleware(['web', 'auth', 'ip.manager'])
    ->names('admin.customers');

Route::resource('admin/membership-settings', MembershipSettingController::class)
    ->middleware(['web', 'auth', 'ip.manager'])
    ->names('admin.membership-settings');
