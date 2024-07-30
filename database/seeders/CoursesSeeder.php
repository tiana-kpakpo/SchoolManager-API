<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define departments
        $departments = [
            ['name' => 'Computer Science', 'code' => 'CS'],
            ['name' => 'Mechanical Engineering', 'code' => 'ME'],
            
        ];

        // Define semesters
        $semesters = [
            ['name' => 'Spring', 'year' => 2024],
            ['name' => 'Fall', 'year' => 2024],
           
        ];

        // Define academic years
        $academicYears = [
            ['name' => '2023/2024'],
            ['name' => '2024/2025'],
           
        ];

        // Insert Academic Years
        foreach ($academicYears as $academicYear) {
            DB::table('academic_years')->updateOrInsert(['name' => $academicYear['name']], $academicYear);
        }

        // Insert Semesters
        foreach ($semesters as $semester) {
            DB::table('semesters')->updateOrInsert([
                'name' => $semester['name'],
                'year' => $semester['year'],
            ], $semester);
        }

        // Insert Departments
        foreach ($departments as $department) {
            DB::table('departments')->updateOrInsert(['code' => $department['code']], $department);
        }

        // Example Courses with department_id
        $courses = [
            ['name' => 'Introduction to Programming', 'code' => 'CS101', 'semester_id' => 1, 'description' => 'A beginner\'s course in programming.', 'department_code' => 'CS', 'year' => 1, 'semester' => 1],
            ['name' => 'Advanced Database Systems', 'code' => 'CS202', 'semester_id' => 2, 'description' => 'An advanced course on database systems.', 'department_code' => 'CS', 'year' => 2, 'semester' => 2],
            ['name' => 'Mathematics 1', 'code' => 'CS201', 'semester_id' => 1, 'description' => 'Basic Mathematics.', 'department_code' => 'CS', 'year' => 1, 'semester' => 1],
            ['name' => 'Robotics', 'code' => 'CS204', 'semester_id' => 2, 'description' => 'Robotics.', 'department_code' => 'CS', 'year' => 2, 'semester' => 2],
            ['name' => 'Thermodynamics', 'code' => 'ME101', 'semester_id' => 1, 'description' => 'Fundamentals of Thermodynamics.', 'department_code' => 'ME', 'year' => 1, 'semester' => 1],
            ['name' => 'Fluid Mechanics', 'code' => 'ME202', 'semester_id' => 2, 'description' => 'Principles of Fluid Mechanics.', 'department_code' => 'ME', 'year' => 2, 'semester' => 2],
            // Add more courses with appropriate department_code, year, and semester
        ];

        // Insert Courses
        foreach ($courses as $course) {
            // Get the department_id based on department_code
            $department = DB::table('departments')->where('code', $course['department_code'])->first();

            if ($department) {
                DB::table('courses')->updateOrInsert([
                    'name' => $course['name'],
                    'code' => $course['code'],
                    'semester_id' => $course['semester_id'],
                    'department_id' => $department->id,
                ], [
                    'name' => $course['name'],
                    'code' => $course['code'],
                    'description' => $course['description'],
                    'semester_id' => $course['semester_id'],
                    'department_id' => $department->id,
                    'year' => $course['year'],
                    'semester' => $course['semester'],
                ]);
            }
        }
    }
}
