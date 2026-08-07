<?php

namespace App\Models;

use App\Enums\PostPriorityLevelEnum;
use App\Enums\PostTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $primaryKey = 'post_id';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'image',
        'body',
        'status',
        'type',
        'priority_level',
    ];

    protected $casts = [
        'type' => PostTypeEnum::class,
        'priority_level' => PostPriorityLevelEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }

    public function blogCategories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_categories', 'post_id', 'category_id');
    }

    public function postView()
    {
        return $this->hasOne(PostView::class, 'post_id', 'post_id');
    }
}
