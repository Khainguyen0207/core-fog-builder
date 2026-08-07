<?php

namespace App\Models;

use App\Enums\CustomerMemberShipEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'phone',
        'user_id',
        'membership_code',
        'total_spent',
        'note',
    ];

    protected $casts = [
        'membership_code' => CustomerMemberShipEnum::class,
        'total_spent' => 'decimal:2',
    ];

    public function membershipSetting(): BelongsTo
    {
        return $this->belongsTo(MembershipSetting::class, 'membership_code', 'membership_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function staffReviews()
    {
        return $this->hasMany(StaffReview::class);
    }
}
