<?php

use App\Http\Controllers\API\AuthenticateController;
use App\Http\Controllers\API\GoogleAuthController;
use App\Http\Controllers\API\Staff\ProfileController;
use App\Http\Middleware\IpManagerMiddleware;
use App\Http\Middleware\StaffAreaMiddleware;
use App\Http\Middleware\VerifyWebMiddleware;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\API',
    'middleware' => [VerifyWebMiddleware::class, 'api', IpManagerMiddleware::class],
], function () {
    Route::group([
        'prefix' => 'auth',
        'middleware' => ['throttle:api-auth']
    ], function () {
        Route::post('login', 'AuthenticateController@login');
        Route::post('login', 'AuthenticateController@login');
        Route::post('register', 'AuthenticateController@register');

        Route::post('forget-password', 'AuthenticateController@forgetPassword');
        Route::post('reset-password', 'AuthenticateController@resetPassword');

        Route::get('verify-otp', 'AuthenticateController@verifyOTP');
        Route::post('verify-otp', 'AuthenticateController@verifyOtpPost');

        Route::post('google/complete-profile', 'GoogleAuthController@completeProfile');
        Route::post('google', 'GoogleAuthController@login');
        Route::get('check-customer', 'GoogleAuthController@checkCustomer');
    });

    Route::group([
        'prefix' => 'staff',
        'middleware' => ['throttle:api-auth']
    ], function () {
        Route::post('login', [AuthenticateController::class, 'staffLogin']);

        Route::post('forget-password', 'AuthenticateController@staffForgetPassword');
        Route::post('reset-password', 'AuthenticateController@staffResetPassword');

        Route::get('verify-otp', 'AuthenticateController@staffVerifyOTP');
        Route::post('verify-otp', 'AuthenticateController@staffVerifyOtpPost');
    });

    Route::group([
        'prefix' => 'staff',
        'namespace' => 'Staff',
        'middleware' => ['auth:sanctum', StaffAreaMiddleware::class],
    ], function () {
        Route::get('dashboard', 'DashboardController@index');
        Route::get('schedules', 'ScheduleController@index');
        Route::get('reviews', 'ReviewController@index');
        Route::patch('booking-services/{bookingService}/status', 'BookingServiceController@updateStatus');

        Route::patch('profile', [ProfileController::class, 'updateProfile']);
        Route::patch('change-password', [ProfileController::class, 'changePassword']);
    });

    Route::get('google/callback', [GoogleAuthController::class, 'googleCallback']);

    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('me', 'AuthenticateController@me');
        Route::get('membership', 'MembershipController@getMembership');

        Route::group([
            'prefix' => 'customers',
            'middleware' => ['throttle:api-auth']
        ], function () {
            Route::post('/update-profile', 'CustomerController@updateProfile');
            Route::post('/change-password', 'CustomerController@changePassword');
        });

        Route::group([
            'prefix' => 'posts',
        ], function () {
            Route::post('/{slug}/comments', 'PostController@comment');
        });

        Route::group([
            'prefix' => 'bookings',
        ], function () {
            Route::get('/', 'BookingController@getBookings');
            Route::get('/{booking_code}', 'BookingController@getBooking');
            Route::post('/cancel', 'BookingController@cancel');
        });

        Route::group([
            'prefix' => 'staff-reviews',
        ], function () {
            Route::get('/', 'StaffReviewController@index');
            Route::post('/', 'StaffReviewController@store');
            Route::get('/booking-service/{bookingService}', 'StaffReviewController@show');
            Route::get('/staff/{staffId}/stats', 'StaffReviewController@staffStats');
        });

        Route::get('logout', 'AuthenticateController@logout');
    });

    Route::group([
        'prefix' => 'posts',
    ], function () {
        Route::get('/{slug}', 'PostController@getPost');
        Route::get('/', 'PostController@getPosts');
    });

    Route::group([
        'prefix' => 'payment',
    ], function () {
        Route::get('payment-methods', 'PaymentController@getPaymentMethods');
        Route::post('create-transaction', 'PaymentController@createTransaction');
        Route::get('status/{transactionCode}', 'PaymentController@getTransactionStatus');

        Route::post('change-payment-method', 'PaymentController@changeTransactionPaymentMethod');
        Route::post('transaction/renew', 'PaymentController@renewTransaction');
        Route::post('check-token', 'PaymentController@checkTransactionByToken');
    });

    Route::post('get-calendar-available', 'BookingController@getCalendar');
    Route::post('collect', 'BookingController@collect');
    Route::get('get-calendar-work', 'PublicController@getCalendarWork');
    Route::get('system-settings', 'PublicController@getSystemSettings');

    Route::get('services', 'ServiceController@getServices');
    Route::post('booking', 'BookingController@create');

    Route::get('staffs', 'StaffController@getStaffs');
    Route::get('staffs/staff', 'StaffController@getStaffByID');

    Route::get('staffs/available-at', 'StaffController@getAvailableStaffs');

    Route::post('coupons/validate', 'CouponController@validateCoupon');

    Route::get('get-booking-by-booking-code', 'PublicController@getBookingByBookingCode');
});


Route::get('get-port', function () {
    $command = 'ss -tulpn | grep 127.0.0.1';

    $result = Process::run($command);

    if ($result->successful()) {
        return response()->json([
            'status' => 'success',
            'data' => $result->output()
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Không thể kết nối tới WSL'
    ], 500);
});
