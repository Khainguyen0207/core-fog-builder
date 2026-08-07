<?php

namespace App\Models;

use App\Enums\StaffLevelEnum;
use App\Facades\SettingHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    protected $table = 'staffs';

    protected $fillable = [
        'staff_code',
        'name',
        'phone',
        'user_id',
        'level',
        'is_active',
        'salary',
        'joined_at',
        'resigned_at',
        'note',
        'avatar',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'salary' => 'decimal:2',
        'joined_at' => 'datetime',
        'resigned_at' => 'datetime',
        'level' => StaffLevelEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookingServices(): hasMany
    {
        return $this->hasMany(BookingService::class, 'assigned_staff_id');
    }

    public function staffReviews(): HasMany
    {
        return $this->hasMany(StaffReview::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'staff_services')->withTimestamps();
    }

    protected static function booted()
    {
        static::updated(function ($staff) {
            if ($staff->wasChanged('is_active')) {
                $countActiveStaff = Staff::query()
                    ->where('is_active', true)
                    ->count();

                $staffCurrentActive = SettingHelper::get('max_active_staff');

                if ($countActiveStaff < $staffCurrentActive) {
                    SettingHelper::set('max_active_staff', $countActiveStaff);
                }
            }
        });
    }
}
