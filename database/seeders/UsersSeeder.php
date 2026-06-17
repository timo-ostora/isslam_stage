<?php

namespace Database\Seeders;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

/**
 * Creates one demo user per role.
 *
 * ┌────────────────────────────────────────────────────────────┐
 * │  Role         │ Email                    │ Password        │
 * ├────────────────────────────────────────────────────────────┤
 * │  super_admin  │ superadmin@test.com      │ Superadmin      │
 * │  admin        │ admin@test.com           │ Admin           │
 * │  professor    │ professor@test.com       │ Professor       │
 * │  student      │ student@test.com         │ Student         │
 * └────────────────────────────────────────────────────────────┘
 *
 * These credentials are for local/staging only.
 * Rotate or remove them before going to production.
 */
class UsersSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = Utils::getFilamentAuthGuard();

        $users = [
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@test.com',
                'password' => 'Superadmin',
                'role'     => Utils::getSuperAdminName(), // 'super_admin'
            ],
            [
                'name'     => 'Admin User',
                'email'    => 'admin@test.com',
                'password' => 'Admin',
                'role'     => 'admin',
            ],
            [
                'name'     => 'Professor User',
                'email'    => 'professor@test.com',
                'password' => 'Professor',
                'role'     => 'professor',
            ],
            [
                'name'     => 'Student User',
                'email'    => 'student@test.com',
                'password' => 'Student',
                'role'     => 'student',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]
            );

            // Assign role (guard-aware); won't duplicate if re-run
            $user->syncRoles([$data['role']]);

            $this->command->line(
                "  👤  <fg=green>{$data['name']}</> ({$data['email']}) → <fg=yellow>{$data['role']}</>"
            );
        }

        $this->command->info('✅  Demo users seeded.');
    }
}