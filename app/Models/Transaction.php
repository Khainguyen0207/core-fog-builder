<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentProviderEnum;
use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'provider_id',
        'token',
        'transaction_code',
        'provider_code',
        'customer_name',
        'customer_phone',
        'expired_at',
        'amount',
        'currency',
        'status',
        'payment_method',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
        'amount' => 'integer',
        'status' => TransactionStatusEnum::class,
        'payment_method' => PaymentMethodEnum::class,
        'provider_code' => PaymentProviderEnum::class,
        'expired_at' => 'datetime',
    ];

    public function paymentSetting(): BelongsTo
    {
        return $this->belongsTo(PaymentSetting::class, 'provider_id');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'transaction_code', 'transaction_code');
    }

    protected static function booted()
    {
        static::updated(function ($model) {
            $orginal = $model->getPrevious();
            $changes = $model->getChanges();

            if (
                isset($changes['status'])
                && $orginal['status'] !== $changes['status']
                && $changes['status'] === TransactionStatusEnum::COMPLETED
                && $model->booking->status->getValue() === BookingStatusEnum::PENDING
            ) {
                $model->booking->update([
                    'status' => BookingStatusEnum::CONFIRMED,
                ]);
            }
        }); 
    }
}
