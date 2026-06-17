<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'type' => fake()->randomElement(["quiz","exam","assignment"]),
            'duration_seconds' => fake()->randomNumber(),
            'passing_score' => fake()->randomNumber(),
            'max_attempts' => fake()->randomNumber(),
        ];
    }
}
