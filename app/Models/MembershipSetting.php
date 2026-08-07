<?php

namespace App\Models;

use App\Enums\BasicStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipSetting extends Model
{
    protected $table = 'membership_settings';

    protected $primaryKey = 'membership_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'membership_code',
        'name',
        'min_points',
        'status',
        'description',
    ];

    protected $casts = [
        'min_points' => 'integer',
        'status' => BasicStatusEnum::class
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'membership_code', 'membership_code');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
