<?php

use Illuminate\Support\Facades\Route;
use Modules\Workforce\Http\Admin\Controllers\StaffController;
use Modules\Workforce\Http\Admin\Controllers\StaffReviewController;
use Modules\Workforce\Http\Admin\Controllers\StaffSettingController;

Route::resource('admin/staffs', StaffController::class)
    ->middleware(['web', 'plugin:figure-admin/workforce', 'auth', 'ip.manager'])
    ->names('admin.staffs');

Route::get('admin/staff-reviews', [StaffReviewController::class, 'index'])
    ->middleware(['web', 'plugin:figure-admin/workforce', 'auth', 'ip.manager'])
    ->name('admin.staff-reviews.index');

Route::get('admin/staff-reviews/{staffReview}', [StaffReviewController::class, 'show'])
    ->middleware(['web', 'plugin:figure-admin/workforce', 'auth', 'ip.manager'])
    ->name('admin.staff-reviews.show');

Route::get('admin/settings/max-active-staff', [StaffSettingController::class, 'activeStaff'])
    ->middleware(['web', 'plugin:figure-admin/workforce', 'auth', 'ip.manager'])
    ->name('admin.settings.active-staff.index');

Route::put('admin/settings/max-active-staff', [StaffSettingController::class, 'updateActiveStaff'])
    ->middleware(['web', 'plugin:figure-admin/workforce', 'auth', 'ip.manager'])
    ->name('admin.settings.active-staff.update');
