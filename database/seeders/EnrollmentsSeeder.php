<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds a deliberately varied set of enrollment states across the 3 demo
 * students, so every status (active / completed / cancelled) and every
 * progress range is represented — needed to test the Course<->User pivot,
 * the unique(user_id, course_id) constraint, and later the certificate flow.
 */
class EnrollmentsSeeder extends Seeder
{
    public function run(): void
    {
        $courseBySlug = fn (string $slug) => Course::where('slug', $slug)->first();

        $plan = [
            'student1@test.com' => [
                ['slug' => 'mastering-react-19-and-nextjs', 'status' => 'active', 'progress' => 45.00],
                ['slug' => 'css-grid-and-flexbox-blueprint', 'status' => 'active', 'progress' => 70.00],
                ['slug' => 'seo-mastery-and-content-strategy', 'status' => 'completed', 'progress' => 100.00, 'completed_days_ago' => 5],
            ],
            'student2@test.com' => [
                ['slug' => 'advanced-laravel-design-patterns', 'status' => 'active', 'progress' => 20.00],
                ['slug' => 'aws-certified-solutions-architect-course', 'status' => 'cancelled', 'progress' => 10.00],
            ],
            'student3@test.com' => [
                ['slug' => 'python-for-data-science-and-machine-learning', 'status' => 'completed', 'progress' => 100.00, 'completed_days_ago' => 10],
                ['slug' => 'figma-uiux-component-systems', 'status' => 'completed', 'progress' => 100.00, 'completed_days_ago' => 3],
                ['slug' => 'docker-and-kubernetes-in-production', 'status' => 'active', 'progress' => 55.00],
            ],
        ];

        foreach ($plan as $email => $enrollments) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->command->warn("⚠️  User {$email} not found — run UsersSeeder first.");
                continue;
            }

            foreach ($enrollments as $enrollment) {
                $course = $courseBySlug($enrollment['slug']);

                if (!$course) {
                    $this->command->warn("⚠️  Course slug '{$enrollment['slug']}' not found — check CoursesSeeder ran first.");
                    continue;
                }

                Enrollment::firstOrCreate(
                    ['user_id' => $user->id, 'course_id' => $course->id],
                    [
                        'status'               => $enrollment['status'],
                        'progress_percentage'  => $enrollment['progress'],
                        'completed_at'         => isset($enrollment['completed_days_ago'])
                            ? now()->subDays($enrollment['completed_days_ago'])
                            : null,
                    ]
                );
            }
        }

        $this->command->info('✅  Enrollments seeded.');
    }
}