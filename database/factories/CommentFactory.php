<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'user_id' => $this->faker->boolean(80) ? (User::inRandomOrder()->first()?->id ?? User::factory()) : null,
            'parent_comment_id' => null,
            'comment_body' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['approved', 'pending', 'spam']),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Comment $comment) {
            if ($this->faker->boolean(20) && $comment->post_id) {

                $parent = Comment::where('post_id', $comment->post_id)
                    ->inRandomOrder()
                    ->first();
                if ($parent) {
                    $comment->parent_comment_id = $parent->comment_id;
                }
            }
        });
    }
}
