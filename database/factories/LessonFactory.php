<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'type' => fake()->randomElement(["video","article","pdf","link"]),
            'content_url' => fake()->word(),
            'content_text' => fake()->text(),
            'metadata' => '{}',
            'duration_seconds' => fake()->randomNumber(),
        ];
    }
}
