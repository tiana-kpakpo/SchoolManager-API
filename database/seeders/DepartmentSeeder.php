<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faculties = Faculty::all()->keyBy('code');


        $departments = [
            ['name' => 'Computer Science', 'code' => 'CS', 'faculty_code' => 'ENG'],
            ['name' => 'Mechanical Engineering', 'code' => 'ME', 'faculty_code' => 'ENG'],
            ['name' => 'Business Administration', 'code' => 'BA', 'faculty_code' => 'BUS'],
            ['name' => 'Arts', 'code' => 'ART', 'faculty_code' => 'ART'],
            ['name' => 'Medicine and Health', 'code' => 'MH', 'faculty_code' => 'MED'],
            ['name' => 'Law', 'code' => 'LAW', 'faculty_code' => 'LAW'],
        ];

        foreach ($departments as $department) {
            $faculty = $faculties[$department['faculty_code']] ?? null;
            if ($faculty) {
                Department::updateOrCreate(
                    ['code' => $department['code']],
                    [
                        'name' => $department['name'],
                        'faculty_id' => $faculty->id
                    ]
                );
            }else{
                Log::error('Faculty not found:' . $department['faculty_code']);
            }
        }
    }
}
