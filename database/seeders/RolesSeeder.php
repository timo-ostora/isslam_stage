<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Creates the four LMS roles and assigns permissions.
 *
 * Role hierarchy:
 *   super_admin  → all permissions  (Filament Shield super-admin bypass)
 *   admin        → all permissions EXCEPT role/permission management
 *   professor    → CRUD own courses, modules, lessons, assignments, quizzes
 *   student      → view enrollments + attempts; cannot manage content
 */
class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = Utils::getFilamentAuthGuard();

        // ── Helper ────────────────────────────────────────────────────────────
        $perms = fn (array $names): array => Permission::whereIn('name', $names)
            ->where('guard_name', $guard)
            ->pluck('name')
            ->toArray();

        $likePerms = fn (string $pattern): array => Permission::where('name', 'like', $pattern)
            ->where('guard_name', $guard)
            ->pluck('name')
            ->toArray();

        // ── 1. super_admin ────────────────────────────────────────────────────
        // Shield checks for this role name via Utils::getSuperAdminName().
        // When found, the panel gate returns true for every permission — no
        // explicit permission assignment is required (but we add them anyway
        // so the Shield UI looks correct).
        $superAdmin = Role::firstOrCreate([
            'name'       => Utils::getSuperAdminName(), // 'super_admin' by default
            'guard_name' => $guard,
        ]);
        $superAdmin->syncPermissions(Permission::where('guard_name', $guard)->get());

        // ── 2. admin ──────────────────────────────────────────────────────────
        // Full LMS control; cannot manage roles / permissions directly.
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);

        $adminPermissions = array_merge(
            $likePerms('%_course'),
            $likePerms('%_module'),
            $likePerms('%_lesson'),
            $likePerms('%_assignment'),
            $likePerms('%_quiz'),
            $likePerms('%_quiz_attempt'),
            $likePerms('%_enrollment'),
            $likePerms('%_category'),
            $likePerms('%_tag'),
            $likePerms('%_user'),
            $perms(['page_Dashboard'])
        );
        $admin->syncPermissions($adminPermissions);

        // ── 3. professor ──────────────────────────────────────────────────────
        // Can create/edit their own content; cannot manage users or enrollments.
        $professor = Role::firstOrCreate(['name' => 'professor', 'guard_name' => $guard]);

        $professorPermissions = array_merge(
            $perms([
                // Courses
                'view_any_course', 'view_course', 'create_course', 'update_course',
                // Modules
                'view_any_module', 'view_module', 'create_module', 'update_module',
                // Lessons
                'view_any_lesson', 'view_lesson', 'create_lesson', 'update_lesson',
                // Assignments
                'view_any_assignment', 'view_assignment', 'create_assignment', 'update_assignment',
                // Quizzes
                'view_any_quiz', 'view_quiz', 'create_quiz', 'update_quiz',
                // Quiz attempts (read-only for grading)
                'view_any_quiz_attempt', 'view_quiz_attempt',
                // Enrollments (read-only — to see who's in their course)
                'view_any_enrollment', 'view_enrollment',
                // Categories / Tags (read-only)
                'view_any_category', 'view_category',
                'view_any_tag', 'view_tag',
            ]),
            $perms(['page_Dashboard'])
        );
        $professor->syncPermissions($professorPermissions);

        // ── 4. student ────────────────────────────────────────────────────────
        // Read-only access to their own enrollments and quiz attempts.
        // Most student interaction happens via the front-end (React), not the
        // Filament panel — so panel permissions are intentionally minimal.
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => $guard]);

        $studentPermissions = $perms([
            'view_any_enrollment',
            'view_enrollment',
            'view_any_quiz_attempt',
            'view_quiz_attempt',
            'view_any_course',
            'view_course',
            'view_any_lesson',
            'view_lesson',
            'page_Dashboard',
        ]);
        $student->syncPermissions($studentPermissions);

        $this->command->info('✅  Roles seeded: super_admin, admin, professor, student.');
    }
}