<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\TimeUnitEnum;
use App\Events\BookingStatusChangedEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'transaction_code',
        'booking_code',
        'customer_id',
        'customer_name',
        'customer_phone',
        'notify_email',
        'scheduled_start',
        'estimated_end',
        'actual_start',
        'actual_end',
        'status',
        'bike_type',
        'plate_number',
        'price',
        'discount',
        'total_price',
        'total_duration',
        'payment_method',
        'coupon_code',
        'note',
    ];

    protected $casts = [
        'scheduled_start' => 'datetime',
        'estimated_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_duration' => 'integer',
        'status' => BookingStatusEnum::class,
        'time_unit' => TimeUnitEnum::class,
        'payment_method' => PaymentMethodEnum::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    public function bookingServices()
    {
        return $this->hasMany(BookingService::class, 'booking_id');
    }

    public function getEstimatedEnd(Booking $booking): Carbon
    {
        return match ($booking->time_unit) {
            // TimeUnitEnum::HOUR => Carbon::parse($this->scheduled_start)->addHours($booking->total_duration),
            TimeUnitEnum::MINUTE => Carbon::parse($this->scheduled_start)->addMinutes($booking->total_duration),
            default => null,
        };
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_code', 'transaction_code');
    }


    protected static function booted()
    {
        static::updated(function (Booking $booking) {
            if ($booking->wasChanged('status') && $booking->status->getValue() === BookingStatusEnum::DONE) {
                event(new BookingStatusChangedEvent($booking));
            }
        });
    }
}
