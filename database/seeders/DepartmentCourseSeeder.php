<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\Course;
use App\Models\Semester;

class DepartmentCourseSeeder extends Seeder
{
    public function run()
    {
        // Define departments
        $departments = [
            ['name' => 'Computer Science', 'code' => 'CS'],
            ['name' => 'Mechanical Engineering', 'code' => 'ME'],
            ['name' => 'Business Administration', 'code' => 'BA'],
            ['name' => 'Arts', 'code' => 'ART'],
            ['name' => 'Medicine and Health', 'code' => 'MH'],
            ['name' => 'Law', 'code' => 'LAW'],
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

        // Define fees
        $fees = [
            'CS' => 5000.00,
            'ME' => 7000.00,
            'BA' => 4500.00,
            'ART' => 2500.00,
            'MH' => 12000.00,
            'LAW' => 9000.00,
        ];

        // Insert Academic Years
        foreach ($academicYears as $academicYear) {
            DB::table('academic_years')->updateOrInsert(['name' => $academicYear['name']], $academicYear);
        }

        // Insert Semesters
        foreach ($semesters as $semester) {
            Semester::updateOrInsert([
                'name' => $semester['name'],
                'year' => $semester['year'],
            ], $semester);
        }

        // Insert Departments and Courses
        foreach ($departments as $departmentData) {
            $department = Department::updateOrInsert(['code' => $departmentData['code']], $departmentData);

            // Insert Fees
            DB::table('fees')->updateOrInsert([
                'department_id' => $department->id,
                'amount' => $fees[$departmentData['code']],
            ]);

            $coursesData = $this->getCoursesData();

            foreach ($coursesData[$departmentData['name']] as $year => $semesters) {
                foreach ($semesters as $semester => $courses) {
                    foreach ($courses as $index => $courseName) {
                        Course::updateOrInsert([
                            'name' => $courseName,
                            'code' => strtoupper(substr($departmentData['name'], 0, 3)) . "-Y{$year}S{$semester}C" . ($index + 1),
                            'description' => "Description for $courseName in Department {$departmentData['name']} Year $year Semester $semester",
                            'department_id' => $department->id,
                            'year' => $year,
                            'semester' => $semester,
                        ]);
                    }
                }
            }
        }
    }

    private function getCoursesData()
    {
        return [
            'Computer Science' => [
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
            'Mechanical Engineering' => [
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
            'Business Administration' => [
                1 => [
                    1 => ['Introduction to Business', 'Principles of Management', 'Microeconomics', 'Business Mathematics'],
                    2 => ['Marketing Management', 'Financial Accounting', 'Organizational Behavior', 'Business Law'],
                ],
                2 => [
                    1 => ['Business Statistics', 'Human Resource Management', 'Macroeconomics', 'Cost Accounting'],
                    2 => ['Operations Management', 'Financial Management', 'Business Ethics', 'International Business'],
                ],
                3 => [
                    1 => ['Business Statistics', 'Human Resource Management', 'Macroeconomics', 'Cost Accounting'],
                    2 => ['Operations Management', 'Financial Management', 'Business Ethics', 'International Business'],
                ],
                4 => [
                    1 => ['Business Statistics', 'Human Resource Management', 'Macroeconomics', 'Cost Accounting'],
                    2 => ['Operations Management', 'Financial Management', 'Business Ethics', 'International Business'],
                ],
            ],
            'Arts' => [
                1 => [
                    1 => ['Art History', 'Drawing I', 'Introduction to Sculpture', 'Color Theory'],
                    2 => ['Painting I', 'Printmaking', 'Art Criticism', 'Ceramics'],
                ],
                2 => [
                    1 => ['Photography', 'Digital Media', 'Advanced Drawing', 'Art and Society'],
                    2 => ['Advanced Painting', 'Sculpture II', 'Installation Art', 'Art Therapy'],
                ],
                3 => [
                    1 => ['Photography', 'Digital Media', 'Advanced Drawing', 'Art and Society'],
                    2 => ['Advanced Painting', 'Sculpture II', 'Installation Art', 'Art Therapy'],
                ],
                4 => [
                    1 => ['Photography', 'Digital Media', 'Advanced Drawing', 'Art and Society'],
                    2 => ['Advanced Painting', 'Sculpture II', 'Installation Art', 'Art Therapy'],
                ],
            ],
            'Medicine and Health' => [
                1 => [
                    1 => ['Anatomy I', 'Physiology I', 'Biochemistry', 'Medical Terminology'],
                    2 => ['Anatomy II', 'Physiology II', 'Microbiology', 'Medical Ethics'],
                ],
                2 => [
                    1 => ['Pathology', 'Pharmacology', 'Clinical Skills', 'Epidemiology'],
                    2 => ['Internal Medicine', 'Pediatrics', 'Surgery', 'Obstetrics and Gynecology'],
                ],
                3 => [
                    1 => ['Pathology', 'Pharmacology', 'Clinical Skills', 'Epidemiology'],
                    2 => ['Internal Medicine', 'Pediatrics', 'Surgery', 'Obstetrics and Gynecology'],
                ],
                4 => [
                    1 => ['Pathology', 'Pharmacology', 'Clinical Skills', 'Epidemiology'],
                    2 => ['Internal Medicine', 'Pediatrics', 'Surgery', 'Obstetrics and Gynecology'],
                ],
            ],
            'Law' => [
                1 => [
                    1 => ['Introduction to Law', 'Legal Research and Writing', 'Constitutional Law', 'Contracts'],
                    2 => ['Criminal Law', 'Property Law', 'Civil Procedure', 'Legal Ethics'],
                ],
                2 => [
                    1 => ['Evidence', 'Family Law', 'Commercial Law', 'Human Rights Law'],
                    2 => ['International Law', 'Administrative Law', 'Environmental Law', 'Intellectual Property Law'],
                ],
                3 => [
                    1 => ['Evidence', 'Family Law', 'Commercial Law', 'Human Rights Law'],
                    2 => ['International Law', 'Administrative Law', 'Environmental Law', 'Intellectual Property Law'],
                ],
                4 => [
                    1 => ['Evidence', 'Family Law', 'Commercial Law', 'Human Rights Law'],
                    2 => ['International Law', 'Administrative Law', 'Environmental Law', 'Intellectual Property Law'],
                ],
            ],
        ];
    }
}
