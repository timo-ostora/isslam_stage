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
 *   professor    → CRUD own courses, modules, lessons, assessments
 *   student      → view enrollments + attempts; cannot manage content
 *
 * FIX vs. original: patterns updated to match real resources (module,
 * assessment, attempt, certificate) — removed non-existent 'assignment',
 * 'quiz', 'quiz_attempt', 'tag'.
 */
class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = Utils::getFilamentAuthGuard();

        $perms = fn (array $names): array => Permission::whereIn('name', $names)
            ->where('guard_name', $guard)
            ->pluck('name')
            ->toArray();

        $likePerms = fn (string $pattern): array => Permission::where('name', 'like', $pattern)
            ->where('guard_name', $guard)
            ->pluck('name')
            ->toArray();

        // ── 1. super_admin ────────────────────────────────────────────────────
        $superAdmin = Role::firstOrCreate([
            'name'       => Utils::getSuperAdminName(),
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
            $likePerms('%_assessment'),
            $likePerms('%_enrollment'),
            $likePerms('%_attempt'),
            $likePerms('%_certificate'),
            $likePerms('%_category'),
            $likePerms('%_user'),
            $perms(['page_Dashboard'])
        );
        $admin->syncPermissions($adminPermissions);

        // ── 3. professor ──────────────────────────────────────────────────────
        // Can create/edit their own content; cannot manage users or enrollments.
        // No delete permissions — professors archive (status=draft) rather than
        // hard-delete, since deleting a course cascades through the whole tree.
        $professor = Role::firstOrCreate(['name' => 'professor', 'guard_name' => $guard]);

        $professorPermissions = array_merge(
            $perms([
                'view_any_course', 'view_course', 'create_course', 'update_course',
                'view_any_module', 'view_module', 'create_module', 'update_module', 'reorder_module',
                'view_any_lesson', 'view_lesson', 'create_lesson', 'update_lesson',
                'view_any_assessment', 'view_assessment', 'create_assessment', 'update_assessment',
                'view_any_attempt', 'view_attempt', // read-only, for grading/review
                'view_any_enrollment', 'view_enrollment', // read-only — who's in their course
                'view_any_category', 'view_category',
            ]),
            $perms(['page_Dashboard'])
        );
        $professor->syncPermissions($professorPermissions);

        // ── 4. student ────────────────────────────────────────────────────────
        // Read-only access to their own enrollments, attempts, and certificates.
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => $guard]);

        $studentPermissions = $perms([
            'view_any_enrollment', 'view_enrollment',
            'view_any_attempt', 'view_attempt',
            'view_any_certificate', 'view_certificate',
            'view_any_course', 'view_course',
            'view_any_lesson', 'view_lesson',
            'page_Dashboard',
        ]);
        $student->syncPermissions($studentPermissions);

        $this->command->info('✅  Roles seeded: super_admin, admin, professor, student.');
    }
}