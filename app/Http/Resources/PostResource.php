<?php

namespace App\Http\Resources;

use App\Enums\PostTypeEnum;
use App\Enums\UserGroupRoleEnum;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends AbstractResource
{
    public function toArray(Request $request): array
    {
        $user = $this->resource->user;

        $name = match ($user?->group_role->getValue()) {
            UserGroupRoleEnum::CUSTOMER => $user->customer?->name,
            UserGroupRoleEnum::STAFF => $user->staff?->name,
            default => 'Admin',
        };

        $comments = $this->whenLoaded('comments', function () {
            return $this->resource
                ->comments()
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->paginate(50);
        });

        return [
            'post_id' => $this->resource->post_id,
            'author' => $name,
            'type' => $this->transformEnum($this->resource->type),
            'priority_level' => $this->transformEnum($this->resource->priority_level),
            'is_new' => $this->resource->updated_at->gt(now()->subDays(3)),
            'blogCategories' => $this->resource->blogCategories,
            'comments' => CommentResource::collection($comments),
            'slug' => $this->resource->slug,
            'image' => asset(Storage::url($this->resource->image)),
            'title' => $this->resource->title,
            'body' => $this->resource->body,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
