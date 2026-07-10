<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\ModuleItem;
use Illuminate\Database\Seeder;

/**
 * For every module: 2 lessons (video + article) then 1 assessment (quiz),
 * wired together through the polymorphic module_items table. This is the
 * seeder that actually exercises the ModuleItem polymorphic design.
 */
class ModuleContentSeeder extends Seeder
{
    public function run(): void
    {
        Module::with('course')->get()->each(function (Module $module) {
            if ($module->moduleItems()->exists()) {
                return; // idempotent re-run
            }

            $courseTitle = $module->course->title;

            $lesson1 = Lesson::create([
                'title'            => "{$module->title}: Video Walkthrough",
                'description'      => "A guided video walkthrough covering {$module->title} for {$courseTitle}.",
                'type'             => 'video',
                'content_url'      => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content_text'     => null,
                'metadata'         => ['captions' => true, 'quality' => '1080p'],
                'duration_seconds' => 720,
            ]);

            $lesson2 = Lesson::create([
                'title'            => "{$module->title}: Written Guide",
                'description'      => "A written reference guide for {$module->title}.",
                'type'             => 'article',
                'content_url'      => null,
                'content_text'     => "This article covers the key ideas behind {$module->title} in {$courseTitle}, with code samples and diagrams to reinforce the video lesson.",
                'metadata'         => ['reading_time_minutes' => 8],
                'duration_seconds' => 480,
            ]);

            $assessment = Assessment::create([
                'title'             => "{$module->title} Quiz",
                'description'       => "Check your understanding of {$module->title} before moving on.",
                'type'              => 'quiz',
                'duration_seconds'  => 600,
                'passing_score'     => 70,
                'max_attempts'      => 3,
            ]);

            foreach ([$lesson1, $lesson2, $assessment] as $position => $itemable) {
                ModuleItem::create([
                    'module_id'     => $module->id,
                    'position'      => $position,
                    'itemable_id'   => $itemable->id,
                    'itemable_type' => $itemable::class,
                ]);
            }
        });

        $this->command->info('✅  Module content (lessons + assessments) seeded.');
    }
}