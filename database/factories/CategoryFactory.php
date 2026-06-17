<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'parent_id' => Category::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
        ];
    }
}
