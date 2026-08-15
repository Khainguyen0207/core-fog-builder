<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingService extends Model
{
    protected $table = 'booking_services';

    protected $fillable = [
        'booking_id',
        'service_id',
        'service_name',
        'price',
        'duration',
        'status',
        'assigned_staff_id',
        'note',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'status' => BookingStatusEnum::class,
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    public function staffReview(): HasOne
    {
        return $this->hasOne(StaffReview::class);
    }
}
