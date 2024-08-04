<?php

namespace Database\Seeders;

use App\Models\User;
use CoursesTableSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call(AdminSeeder::class);

        $this->call([
            AdminSeeder::class,
            AcademicYearSeeder::class,
            FeesTableSeeder::class,
            DepartmentSeeder::class,
            SemestersSeeder::class,
            CoursesSeeder::class,
            // DepartmentCourseSeeder::class,
                        
        ]);
        
    }
}
