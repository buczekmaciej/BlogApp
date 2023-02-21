<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(4, 10));

        return [
            'content' => fake()->realTextBetween(200, 800),
            'title' => $title,
            'slug' => implode("-", explode(" ", strtolower($title))),
        ];
    }
}
