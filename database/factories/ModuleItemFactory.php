<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'module_id' => Module::factory(),
            'position' => fake()->numberBetween(-10000, 10000),
        ];
    }
}
