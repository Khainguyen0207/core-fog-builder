<?php

namespace App\Http\Resources;

use App\Enums\UserGroupRoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->resource->user;

        $name = match ($user?->group_role->getValue()) {
            UserGroupRoleEnum::CUSTOMER => $user->customer?->name,
            UserGroupRoleEnum::STAFF => $user->staff?->name,
            default => 'Người sửa ẩn danh',
        };

        return [
            'comment_id' => $this->resource->comment_id,
            'post_id' => $this->resource->post_id,
            'user_id' => $user?->id,
            'user_name' => $name,
            'parent_comment_id' => $this->resource->parent_comment_id,
            'comment_body' => $this->resource->comment_body,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
