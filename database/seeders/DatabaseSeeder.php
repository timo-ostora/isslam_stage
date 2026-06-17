<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();  
        $this->call([
            ShieldPermissionsSeeder::class, // must run first — generates all permissions
            RolesSeeder::class,             // creates roles and assigns permissions
            UsersSeeder::class,             // creates one demo user per role
            CategoriesSeeder::class,
            CoursesSeeder::class,
        ]); 
    }
}
