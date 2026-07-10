<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // FIX: was DB::table('users')->skip(2)->first() — fragile, relied on
        // insertion order. Look the professor up by role instead.
        $professorId = DB::table('users')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'professor')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->value('users.id');

        if (!$professorId) {
            $this->command->warn('⚠️  No professor user found — run UsersSeeder + RolesSeeder first.');
            return;
        }

        $subCategories = DB::table('categories')
            ->whereNotNull('parent_id')
            ->get()
            ->groupBy('title');

        // FIX: real, distinct placeholder images per course (picsum.photos with
        // a fixed seed per slug so thumbnails are stable across re-seeds)
        // instead of one hardcoded, likely-nonexistent local path.
        $thumb = fn (string $slug): string => "https://picsum.photos/seed/{$slug}/640/360";

        $courseData = [
            'Frontend Development' => [
                ['title' => 'Mastering React 19 and Next.js', 'diff' => 'medium', 'duration' => 28800],
                ['title' => 'CSS Grid and Flexbox Blueprint', 'diff' => 'easy', 'duration' => 10800],
            ],
            'Backend Development' => [
                ['title' => 'Advanced Laravel Design Patterns', 'diff' => 'hard', 'duration' => 36000],
                ['title' => 'Building Scalable APIs with Node.js', 'diff' => 'medium', 'duration' => 21600],
            ],
            'Machine Learning' => [
                ['title' => 'Python for Data Science and Machine Learning', 'diff' => 'easy', 'duration' => 25200],
                ['title' => 'Deep Learning with TensorFlow and PyTorch', 'diff' => 'hard', 'duration' => 39600],
            ],
            'Digital Marketing' => [
                ['title' => 'SEO Mastery and Content Strategy', 'diff' => 'easy', 'duration' => 14400],
                ['title' => 'Google Ads and Performance Marketing', 'diff' => 'medium', 'duration' => 18000],
            ],
            'UI/UX Design' => [
                ['title' => 'Figma UI/UX Component Systems', 'diff' => 'medium', 'duration' => 16200],
                ['title' => 'User Research Methodologies', 'diff' => 'easy', 'duration' => 12600],
            ],
            'Cloud Computing' => [
                ['title' => 'AWS Certified Solutions Architect Course', 'diff' => 'hard', 'duration' => 43200],
                ['title' => 'Docker and Kubernetes in Production', 'diff' => 'hard', 'duration' => 32400],
            ],
        ];

        foreach ($courseData as $categoryName => $courses) {
            $category = $subCategories->get($categoryName)?->first();

            if (!$category) {
                continue;
            }

            foreach ($courses as $course) {
                $title = $course['title'];
                $slug = Str::slug($title);

                DB::table('courses')->insertOrIgnore([
                    'slug' => $slug,
                    'category_id' => $category->id,
                    'creator_id' => $professorId,
                    'title' => $title,
                    'description' => "Accelerate your career with this comprehensive guide on {$title}. Includes real-world projects and quizzes.",
                    'thumbnail_url' => $thumb($slug),
                    'status' => 'published',
                    'duration_seconds' => $course['duration'],
                    'difficulty_level' => $course['diff'],
                    'language' => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('✅  Courses seeded.');
    }
}