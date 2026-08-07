<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OneTimePassword extends Model
{
    protected $table = 'one_time_password';

    protected $fillable = [
        'user_id',
        'email',
        'token',
        'code',
        'sent_at',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
}
