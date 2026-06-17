<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->slug(),
            'category_id' => Category::factory(),
            'creator_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'thumbnail_url' => fake()->word(),
            'status' => fake()->randomElement(["draft","published","archived"]),
            'difficulty_level' => fake()->randomElement(["easy","medium","hard"]),
            'language' => fake()->word(),
        ];
    }
}
