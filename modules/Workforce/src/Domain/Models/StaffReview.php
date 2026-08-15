<?php

namespace App\Models;

use App\Plugins\PluginManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffReview extends Model
{
    protected $table = 'staff_reviews';

    protected $fillable = [
        'customer_id',
        'staff_id',
        'booking_service_id',
        'rating',
        'note',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function bookingService(): BelongsTo
    {
        return $this->belongsTo(BookingService::class);
    }

    protected static function booted()
    {
        static::created(function ($review) {
            if (! app(PluginManager::class)->isEnabled('figure-admin/workforce')) {
                return;
            }

            $review->updateStaffRating();
        });
    }

    public function updateStaffRating()
    {
        $staff = $this->staff;
        $staff->rate = $staff->staffReviews()->avg('rating');
        $staff->save();
    }
}
