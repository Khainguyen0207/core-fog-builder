<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Http\Controllers\Admin',
    'middleware' => ['auth', 'ip.manager'],
], function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard.index'));

    Route::resource('dashboard', 'DashboardController');

    Route::resource('coupons', 'CouponController');
    Route::resource('coupon-applicables', 'CouponApplicableController');
    Route::get('coupon-redemptions', [\App\Http\Controllers\Admin\CouponRedemptionController::class, 'index'])->name('coupon-redemptions.index');
    Route::resource('bookings', 'BookingController');

    Route::post('bookings/export', 'BookingController@export')->name('bookings.export');

    Route::resource('booking-services', 'BookingServiceController');
    Route::get('calendar', 'CalendarController@index')->name('calendar.index');
    Route::get('calendar/events', 'CalendarController@events')->name('calendar.events');

    Route::get('email-templates/{emailTemplate}/preview', 'EmailTemplateController@preview')->name('email-templates.preview');
    Route::resource('email-templates', 'EmailTemplateController')->except(['create', 'store']);
    Route::resource('posts', 'PostController');
    Route::resource('blog-categories', 'BlogCategoryController');
    Route::resource('tags', 'TagController');
    Route::resource('comments', 'CommentController');

    Route::get('transactions', 'TransactionController@index')->name('transactions.index');
    Route::get('transactions/{transaction}', 'TransactionController@show')->name('transactions.show');
    Route::put('transactions/{transaction}', 'TransactionController@update')->name('transactions.update');

    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.',
    ], function () {
        Route::get('/', 'SettingController@index')->name('index');
        Route::post('/', 'SettingController@update')->name('store');

        Route::get('/sepay', 'SettingController@sePay')->name('sepay');
        Route::get('/work-time', 'SettingController@workTime')->name('work-time');
        Route::get('/information-system', 'SettingController@informationSystem')->name('information-system');
        Route::get('/telegram', 'SettingController@telegram')->name('telegram');
    });

    Route::get('send-email', 'SendEmailController@index')->name('send-email.index');
    Route::get('send-email/preview/{id}', 'SendEmailController@getTemplatePreview')->name('send-email.preview');
    Route::post('send-email/send', 'SendEmailController@send')->name('send-email.send');

    Route::post('bulk-delete', 'BulkDeleteController@bulkDelete')->name('bulk-delete');

    Route::get('log-viewer', function () {
        return view('log-viewer::log-viewer.index');
    })->name('log-viewer.index');
    Route::get('logout', 'AuthenticationController@logout')->name('logout');
});

Route::group([
    'prefix' => '/',
    'middleware' => ['guest'],
    'namespace' => 'App\Http\Controllers\Admin',
], function () {

    Route::get('updated-activity', 'TelegramBotController@updatedActivity');

    Route::get('login', 'AuthenticationController@login')->name('login');
    Route::post('login', 'AuthenticationController@authenticate')->name('login.authenticate');
});

Route::get('/', function () {
    return redirect()->route('admin.dashboard.index');
})->name('home');
