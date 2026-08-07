<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'category_id',
        'status',
        'price',
        'time_do',
        'time_unit',
        'priority',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'time_do' => 'integer',
        'priority' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function staffs(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'staff_services')->withTimestamps();
    }
}
