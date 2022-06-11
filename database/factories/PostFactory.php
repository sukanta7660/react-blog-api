<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Post::class;

    public function definition()
    {
        $title = $this->faker->sentence;


        return [
            'title' => $title,
            'body' => $this->faker->paragraph,
            'slug' => Str::slug($title),
            'user_id' => User::factory()
        ];
    }
}
