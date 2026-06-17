<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'status' => fake()->randomElement(["active","completed","cancelled"]),
            'progress_percentage' => fake()->randomFloat(2, 0, 999.99),
            'completed_at' => fake()->dateTime(),
        ];
    }
}
