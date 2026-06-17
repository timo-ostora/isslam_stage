<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        // 1. Get the 3rd user from the database to assign ownership
        $thirdUser = DB::table('users')->skip(2)->first();

        // Safety check if user table is empty or has less than 3 records
        if (!$thirdUser) {
            return;
        }

        // 2. Fetch only subcategories (Level 2 children) so courses sit at the lowest level
        $subCategories = DB::table('categories')
            ->whereNotNull('parent_id')
            ->get()
            ->groupBy('title');

        // 3. Define course blueprints mapped directly to your subcategory titles
        $courseData = [
            'Frontend Development' => [
                ['title' => 'Mastering React 19 and Next.js', 'diff' => 'medium'],
                ['title' => 'CSS Grid and Flexbox Blueprint', 'diff' => 'easy'],
            ],
            'Backend Development' => [
                ['title' => 'Advanced Laravel Design Patterns', 'diff' => 'hard'],
                ['title' => 'Building Scalable APIs with Node.js', 'diff' => 'medium'],
            ],
            'Machine Learning' => [
                ['title' => 'Python for Data Science and Machine Learning', 'diff' => 'easy'],
                ['title' => 'Deep Learning with TensorFlow and PyTorch', 'diff' => 'hard'],
            ],
            'Digital Marketing' => [
                ['title' => 'SEO Mastery and Content Strategy', 'diff' => 'easy'],
                ['title' => 'Google Ads and Performance Marketing', 'diff' => 'medium'],
            ],
            'UI/UX Design' => [
                ['title' => 'Figma UI/UX Component Systems', 'diff' => 'medium'],
                ['title' => 'User Research Methodologies', 'diff' => 'easy'],
            ],
            'Cloud Computing' => [
                ['title' => 'AWS Certified Solutions Architect Course', 'diff' => 'hard'],
                ['title' => 'Docker and Kubernetes in Production', 'diff' => 'hard'],
            ],
        ];

        // 4. Seed courses into the database
        foreach ($courseData as $categoryName => $courses) {
            // Find the subcategory ID matching this array key
            $category = $subCategories->get($categoryName)?->first();

            if (!$category) {
                continue; // Skip if subcategory wasn't seeded by CategorySeeder
            }

            foreach ($courses as $course) {
                $title = $course['title'];
                
                DB::table('courses')->insert([
                    'slug' => Str::slug($title),
                    'category_id' => $category->id, // Uses the auto-incremented numerical ID
                    'creator_id' => $thirdUser->id,  // Uses the user numerical ID
                    'title' => $title,
                    'description' => "Accelerate your career with this comprehensive guide on {$title}. Includes real-world projects and quizzes.",
                    'thumbnail_url' => 'course-thumbnails/01KTSR0JY8TB0Y7EE28Q42QZ65.jpg',
                    'status' => 'published', // default is draft, overriding to publish sample courses
                    'difficulty_level' => $course['diff'],
                    'language' => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null, // Ready for soft deletes
                ]);
            }
        }
    }
}
