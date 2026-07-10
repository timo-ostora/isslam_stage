<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Generates every Filament Shield permission for every registered Filament
 * resource + page. Mirrors `php artisan shield:generate --all`.
 *
 * IMPORTANT: this list must match the resources you actually register in
 * app/Filament/Resources — including ones hidden from navigation (e.g.
 * ModuleResource), since Shield generates permissions per-Resource-class,
 * not per-nav-item. It must NOT include models that only ever appear as
 * relation-manager content (Question, QuestionOption, ModuleItem,
 * AttemptAnswer, AttemptAnswerOption) — those have no standalone Resource
 * and are gated by their parent resource's permissions instead.
 *
 * FIX vs. original: removed 'assignment', 'quiz', 'quiz_attempt', 'tag'
 * (none of these models exist in the schema) and added the real resources:
 * 'module', 'assessment', 'attempt', 'certificate'.
 */
class ShieldPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = Utils::getFilamentAuthGuard(); // usually 'web'

        $resources = [
            'category',
            'course',
            'module',
            'lesson',
            'assessment',
            'enrollment',
            'attempt',
            'certificate',
            'user',
            'role',       // Shield's own role resource
            'permission', // Shield's own permission resource
        ];

        $resourceActions = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'reorder',
        ];

        foreach ($resources as $resource) {
            foreach ($resourceActions as $action) {
                Permission::firstOrCreate(
                    ['name' => "{$action}_{$resource}", 'guard_name' => $guard]
                );
            }
        }

        $pages = [
            'page_Dashboard',
        ];

        foreach ($pages as $page) {
            Permission::firstOrCreate(
                ['name' => $page, 'guard_name' => $guard]
            );
        }

        $this->command->info('✅  Shield permissions seeded.');
    }
}