<?php

namespace Database\Seeders;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

/**
 * Creates demo users: one super_admin, one admin, one professor, and three
 * students (so we can seed distinct enrollment states — active, completed,
 * cancelled — across real accounts instead of testing with a single user).
 *
 * ┌────────────────────────────────────────────────────────────┐
 * │  Role         │ Email                    │ Password        │
 * ├────────────────────────────────────────────────────────────┤
 * │  super_admin  │ superadmin@test.com      │ Superadmin      │
 * │  admin        │ admin@test.com           │ Admin           │
 * │  professor    │ professor@test.com       │ Professor       │
 * │  student      │ student1@test.com        │ Student1        │
 * │  student      │ student2@test.com        │ Student2        │
 * │  student      │ student3@test.com        │ Student3        │
 * └────────────────────────────────────────────────────────────┘
 *
 * These credentials are for local/staging only. Rotate or remove before
 * going to production.
 */
class UsersSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $users = [
            ['name' => 'Super Admin',    'email' => 'superadmin@test.com', 'password' => 'Superadmin', 'role' => Utils::getSuperAdminName()],
            ['name' => 'Admin User',     'email' => 'admin@test.com',      'password' => 'Admin',      'role' => 'admin'],
            ['name' => 'Professor User', 'email' => 'professor@test.com',  'password' => 'Professor',  'role' => 'professor'],
            ['name' => 'Amina Student',  'email' => 'student1@test.com',   'password' => 'Student1',   'role' => 'student'],
            ['name' => 'Yassine Student','email' => 'student2@test.com',   'password' => 'Student2',   'role' => 'student'],
            ['name' => 'Sara Student',   'email' => 'student3@test.com',   'password' => 'Student3',   'role' => 'student'],
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

            $user->syncRoles([$data['role']]);

            $this->command->line(
                "  👤  <fg=green>{$data['name']}</> ({$data['email']}) → <fg=yellow>{$data['role']}</>"
            );
        }

        $this->command->info('✅  Demo users seeded.');
    }
}