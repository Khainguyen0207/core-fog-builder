<?php

use Illuminate\Support\Facades\Route;
use Modules\Promotions\Http\Admin\Controllers\CouponApplicableController;
use Modules\Promotions\Http\Admin\Controllers\CouponController;
use Modules\Promotions\Http\Admin\Controllers\CouponRedemptionController;

Route::resource('admin/coupons', CouponController::class)
    ->middleware(['web', 'plugin:figure-admin/promotions', 'auth', 'ip.manager'])
    ->names('admin.coupons');

Route::resource('admin/coupon-applicables', CouponApplicableController::class)
    ->middleware(['web', 'plugin:figure-admin/promotions', 'auth', 'ip.manager'])
    ->names('admin.coupon-applicables');

Route::get('admin/coupon-redemptions', [CouponRedemptionController::class, 'index'])
    ->middleware(['web', 'plugin:figure-admin/promotions', 'auth', 'ip.manager'])
    ->name('admin.coupon-redemptions.index');
