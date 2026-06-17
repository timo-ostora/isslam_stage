<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Generates every Filament Shield permission (view_any, view, create,
 * update, delete, delete_any, force_delete, force_delete_any, restore,
 * restore_any, reorder) for every registered Filament resource + page.
 *
 * This mirrors what `php artisan shield:generate --all` does at runtime,
 * so the seeder is safe to re-run (uses firstOrCreate).
 */
class ShieldPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = Utils::getFilamentAuthGuard(); // usually 'web'

        // ── Resources ────────────────────────────────────────────────────────
        // List every Filament resource slug you have (or will have).
        // Shield derives permission names from these automatically.
        $resources = [
            'course',
            'module',
            'lesson',
            'assignment',
            'quiz',
            'quiz_attempt',
            'enrollment',
            'category',
            'tag',
            'user',
            'role',              // Shield's own role resource
            'permission',       // Shield's own permission resource
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

        // ── Custom Pages / Widgets ────────────────────────────────────────────
        // Shield uses the prefix `page_` for pages and `widget_` for widgets.
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
