<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $now = Carbon::now();

        // Define LMS data structure: Level 1 (Parents) => Level 2 (Children)
        $lmsStructure = [
            'Web Development' => [
                'Frontend Development',
                'Backend Development',
                'Fullstack Frameworks',
                'Mobile App Development'
            ],
            'Data Science & AI' => [
                'Machine Learning',
                'Artificial Intelligence',
                'Data Analysis & Visualization',
                'Big Data Technologies'
            ],
            'Business & Marketing' => [
                'Digital Marketing',
                'Entrepreneurship',
                'Project Management',
                'Financial Analysis'
            ],
            'Design & UX' => [
                'UI/UX Design',
                'Graphic Design',
                'Motion Graphics & Video',
                '3D Modeling'
            ],
            'Information Technology' => [
                'Cloud Computing',
                'Cybersecurity',
                'Network Administration',
                'DevOps Practices'
            ]
        ];

        foreach ($lmsStructure as $parentTitle => $childrenTitles) {
            // 1. Insert Parent Category and get its auto-incremented ID
            $parentId = DB::table('categories')->insertGetId([
                'parent_id' => null, // Top-level
                'title' => $parentTitle,
                'description' => "Master skills in the field of {$parentTitle}.",
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // 2. Insert Child Categories (Level 2)
            foreach ($childrenTitles as $childTitle) {
                DB::table('categories')->insert([
                    'parent_id' => $parentId, // Links to parent numerical ID
                    'title' => $childTitle,
                    'description' => "In-depth courses specializing in {$childTitle}.",
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
