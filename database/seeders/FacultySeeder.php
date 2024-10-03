<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            ['name' => 'Faculty of Engineering', 'code' => 'ENG'],
            ['name' => 'Faculty of Business', 'code' => 'BUS'],
            ['name' => 'Faculty of Arts', 'code' => 'ART'],
            ['name' => 'Faculty of Medicine', 'code' => 'MED'],
            ['name' => 'Faculty of Law', 'code' => 'LAW'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::updateOrCreate(
                ['code' => $faculty['code']],
                $faculty
            );
        }
    
    }
}
