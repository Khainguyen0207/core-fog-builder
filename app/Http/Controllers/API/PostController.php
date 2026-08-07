<?php

namespace App\Http\Controllers\API;

use App\Enums\BasicStatusEnum;
use App\Enums\PostTypeEnum;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController
{
    public function getPost(string $slug)
    {
        $post = Post::query()
            ->with(['user.staff', 'user.customer', 'postView', 'comments', 'blogCategories'])
            ->where('status', BasicStatusEnum::PUBLISHED)
            ->where('slug', $slug)->firstOrFail();

        $user = request()->user();

        if ($user && $user->staff) {
            $post->where('type', PostTypeEnum::INTERNAL);
        } else {
            $post->where('type', PostTypeEnum::COMMUNITY);
        }

        return [
            'error' => false,
            'data' => PostResource::make($post),
            'message' => 'Lấy bài viết thành công.',
        ];
    }

    public function getPosts()
    {
        $posts = Post::query()
            ->where('status', BasicStatusEnum::PUBLISHED);

        $postNews = Post::query()
            ->where('updated_at', '>', now()->subDays(3));

        $user = Auth::guard('sanctum')->user();

        if ($user && $user->staff) {
            $posts = $posts->where('type', PostTypeEnum::INTERNAL);
            $postNews = $postNews->where('type', PostTypeEnum::INTERNAL);
        } else {
            $posts = $posts->where('type', PostTypeEnum::COMMUNITY);
            $postNews = $postNews->where('type', PostTypeEnum::COMMUNITY);
        }

        $postNews = $postNews->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        $posts = $posts->orderByDesc('created_at')->paginate(6);

        return [
            'error' => false,
            'data' => PostResource::collection($posts),
            'meta' => [
                'post_news' => PostResource::collection($postNews),
                'count_new' => $postNews->count(),
            ],
            'message' => 'Lấy danh sách bài viết thành công.',
        ];
    }

    public function comment(Request $request)
    {
        $status = 'approved';

        $request->validate([
            'post_id' => 'required|string|exists:posts,post_id',
            'comment_body' => 'required|string',
            'parent_comment_id' => 'nullable|string|exists:comments,comment_id',
        ]);

        $user = $request->user();

        $cmt = Comment::query()->create([
            'user_id' => $user->id,
            'post_id' => $request->post_id,
            'comment_body' => $request->comment_body,
            'parent_comment_id' => $request->parent_comment_id,
            'status' => $status,
        ])->load('user');

        return response()->json([
            'error' => false,
            'data' => CommentResource::make($cmt),
            'message' => 'Bình luận thành công.',
        ]);
    }
}
