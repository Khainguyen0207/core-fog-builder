<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostView;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostViewFactory extends Factory
{
    protected $model = PostView::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'view_count' => $this->faker->numberBetween(0, 10000),
            'like_count' => $this->faker->numberBetween(0, 500),
        ];
    }
}
