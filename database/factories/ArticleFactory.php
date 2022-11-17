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
    public function definition()
    {
        return [
            'featured' => fake()->boolean(),
            'title' => fake()->title(),
            'url' => fake()->url(),
            'imageUrl' => fake()->url(),
            'newSite' => fake()->word(),
            'summary' => fake()->text(),
            'publishedAt' => fake()->text(),
        ];
    }
}
