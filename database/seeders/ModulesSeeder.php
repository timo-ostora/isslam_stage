<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Seeder;

/**
 * Creates 3 modules per published course, in a consistent
 * intro → core → advanced progression.
 */
class ModulesSeeder extends Seeder
{
    private const TEMPLATE = [
        ['title' => 'Getting Started', 'description' => 'Foundational concepts and environment setup.'],
        ['title' => 'Core Concepts', 'description' => 'The essential skills this course is built around.'],
        ['title' => 'Advanced Topics & Real-World Projects', 'description' => 'Applying what you learned to production-grade scenarios.'],
    ];

    public function run(): void
    {
        Course::query()->each(function (Course $course) {
            if ($course->modules()->exists()) {
                return; // idempotent re-run
            }

            foreach (self::TEMPLATE as $position => $module) {
                Module::create([
                    'course_id'   => $course->id,
                    'title'       => "{$module['title']}",
                    'description' => $module['description'],
                    'position'    => $position,
                ]);
            }
        });

        $this->command->info('✅  Modules seeded.');
    }
}