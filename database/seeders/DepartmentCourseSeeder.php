<?php

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Course;

class DepartmentCourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Computer Science' => [
                'code' => 'CS',
                'courses' => [
                    1 => [
                        1 => ['Introduction to Programming', 'Mathematics I', 'Physics I', 'Computer Science Basics'],
                        2 => ['Data Structures', 'Mathematics II', 'Physics II', 'Introduction to Databases'],
                    ],
                    2 => [
                        1 => ['Algorithms', 'Discrete Mathematics', 'Operating Systems', 'Digital Logic'],
                        2 => ['Computer Networks', 'Software Engineering', 'Microprocessors', 'Database Systems'],
                    ],
                    3 => [
                        1 => ['Algorithms', 'Discrete Mathematics', 'Operating Systems', 'Digital Logic'],
                        2 => ['Computer Networks', 'Software Engineering', 'Microprocessors', 'Database Systems'],
                    ],
                    4 => [
                        1 => ['Algorithms', 'Discrete Mathematics', 'Operating Systems', 'Digital Logic'],
                        2 => ['Computer Networks', 'Software Engineering', 'Microprocessors', 'Database Systems'],
                    ],
                ],
            ],
            'Mechanical Engineering' => [
                'code' => 'ME',
                'courses' => [
                    1 => [
                        1 => ['Engineering Mechanics', 'Mathematics I', 'Physics I', 'Engineering Drawing'],
                        2 => ['Thermodynamics', 'Mathematics II', 'Physics II', 'Materials Science'],
                    ],
                    2 => [
                        1 => ['Fluid Mechanics', 'Mechanics of Solids', 'Manufacturing Processes', 'Thermodynamics II'],
                        2 => ['Machine Design', 'Heat Transfer', 'Dynamics of Machinery', 'Control Systems'],
                    ],
                    3 => [
                        1 => ['Fluid Mechanics', 'Mechanics of Solids', 'Manufacturing Processes', 'Thermodynamics II'],
                        2 => ['Machine Design', 'Heat Transfer', 'Dynamics of Machinery', 'Control Systems'],
                    ],
                    4 => [
                        1 => ['Fluid Mechanics', 'Mechanics of Solids', 'Manufacturing Processes', 'Thermodynamics II'],
                        2 => ['Machine Design', 'Heat Transfer', 'Dynamics of Machinery', 'Control Systems'],
                    ],
                ],
            ],
        ];

        foreach ($data as $departmentName => $departmentData) {
            $department = Department::create([
                'name' => $departmentName,
                'code' => $departmentData['code'],
            ]);

            foreach ($departmentData['courses'] as $year => $semesters) {
                foreach ($semesters as $semester => $courseNames) {
                    foreach ($courseNames as $index => $courseName) {
                        Course::create([
                            'name' => $courseName,
                            'code' => strtoupper(substr($departmentName, 0, 3)) . "-Y{$year}S{$semester}C" . ($index + 1),
                            'description' => "Description for $courseName in Department $departmentName Year $year Semester $semester",
                            'department_id' => $department->id,
                            'year' => $year,
                            'semester' => $semester,
                        ]);
                    }
                }
            }
        }
    }
}
