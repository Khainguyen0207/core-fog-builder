<?php

namespace Database\Factories;

use App\Enums\PostPriorityLevelEnum;
use App\Enums\PostTypeEnum;
use App\Models\Post;
use App\Models\PostView;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence();

        return [
            'user_id' => $this->faker->boolean(80) ? (User::inRandomOrder()->first()?->id ?? User::factory()) : null,
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 10000),
            'body' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'type' => PostTypeEnum::COMMUNITY,
            'priority_level' => $this->faker->optional(0.5)->randomElement(PostPriorityLevelEnum::values()),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Post $post) {
            PostView::factory()->create(['post_id' => $post->post_id]);
        });
    }
}
