<?php

namespace Tests\Feature\Plugins;

use App\Actions\CheckTransactionAction;
use App\Actions\SendBookingNotificationAction;
use App\Events\BookingStatusChangedEvent;
use App\Events\TransactionFailedEvent;
use App\Http\Requests\API\BookingRequest;
use App\Jobs\CheckTransactionJob;
use App\Jobs\SendBookingNotificationJob;
use App\Listeners\BookingCompletedListener;
use App\Listeners\TransactionFailedListener;
use App\Models\Booking;
use App\Models\Transaction;
use App\Plugins\PluginManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class PluginExecutionGatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_validation_rejects_disabled_extension_inputs(): void
    {
        $request = BookingRequest::create('/api/v1/booking', 'POST');
        $rules = $request->rules();

        $validator = Validator::make([
            'payment_method' => 'bank_transfer',
            'coupon_code' => 'SAVE10',
        ], [
            'payment_method' => $rules['payment_method'],
            'coupon_code' => $rules['coupon_code'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('payment_method', $validator->errors()->toArray());
        $this->assertArrayHasKey('coupon_code', $validator->errors()->toArray());
    }

    public function test_pay_later_without_a_coupon_remains_valid_when_extensions_are_disabled(): void
    {
        $request = BookingRequest::create('/api/v1/booking', 'POST');
        $rules = $request->rules();

        $validator = Validator::make([
            'payment_method' => 'pay_later',
            'coupon_code' => null,
        ], [
            'payment_method' => $rules['payment_method'],
            'coupon_code' => $rules['coupon_code'],
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_extension_inputs_remain_valid_when_dependencies_are_enabled(): void
    {
        app(PluginManager::class)->replaceEnabledPackages([
            'figure-admin/catalog',
            'figure-admin/workforce',
            'figure-admin/booking',
            'figure-admin/payments',
            'figure-admin/promotions',
        ]);

        $request = BookingRequest::create('/api/v1/booking', 'POST');
        $rules = $request->rules();
        $validator = Validator::make([
            'payment_method' => 'bank_transfer',
            'coupon_code' => null,
        ], [
            'payment_method' => $rules['payment_method'],
            'coupon_code' => $rules['coupon_code'],
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_stale_payment_and_communications_jobs_do_nothing_when_disabled(): void
    {
        $plugins = app(PluginManager::class);
        $action = Mockery::mock(CheckTransactionAction::class);
        $action->shouldNotReceive('handle');

        (new CheckTransactionJob)->handle($action, $plugins);

        Mail::fake();
        (new SendBookingNotificationJob(
            'customer@example.test',
            'Reminder',
            '<p>Reminder</p>',
        ))->handle($plugins);

        Mail::assertNothingSent();
    }

    public function test_disabled_communications_reminder_does_not_query_bookings(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        app(SendBookingNotificationAction::class)->handle();

        $bookingQueries = array_filter(
            DB::getQueryLog(),
            fn (array $query): bool => str_contains($query['query'], 'bookings'),
        );

        $this->assertSame([], array_values($bookingQueries));
    }

    public function test_disabled_plugin_listeners_do_not_touch_event_models(): void
    {
        $plugins = app(PluginManager::class);
        $booking = Mockery::mock(Booking::class);
        $booking->shouldNotReceive('getOriginal');
        $transaction = Mockery::mock(Transaction::class);
        $transaction->shouldNotReceive('update');

        (new BookingCompletedListener($plugins))->handle(new BookingStatusChangedEvent($booking));
        (new TransactionFailedListener($plugins))->handle(new TransactionFailedEvent($transaction));

        $this->addToAssertionCount(1);
    }
}
