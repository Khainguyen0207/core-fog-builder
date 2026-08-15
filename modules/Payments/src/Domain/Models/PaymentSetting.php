<?php

namespace App\Models;

use App\Enums\PaymentProviderEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentSetting extends Model
{
    protected $table = 'payment_settings';

    protected $fillable = [
        'provider_name',
        'is_active',
        'config',
    ];

    protected $casts = [
        'provider_name' => PaymentProviderEnum::class,
        'is_active' => 'boolean',
        'config' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'provider_id');
    }
}
