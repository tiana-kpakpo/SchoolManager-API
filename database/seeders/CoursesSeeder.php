<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get the semesters
        $springSemester = Semester::where('name', 'Spring')->where('year', 2024)->firstOrFail();
        $fallSemester = Semester::where('name', 'Fall')->where('year', 2024)->firstOrFail();

        $departments = Department::all()->keyBy('name');


        $coursesData = [
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
                    1 => ['Introduction to Law', 'Constitutional Law', 'Criminal Law', 'Legal Writing'],
                    2 => ['Contract Law', 'Tort Law', 'Property Law', 'Legal Research'],
                ],
                2 => [
                    1 => ['Civil Procedure', 'Administrative Law', 'International Law', 'Family Law'],
                    2 => ['Corporate Law', 'Labor Law', 'Intellectual Property', 'Legal Ethics'],
                ],
                3 => [
                    1 => ['Civil Procedure', 'Administrative Law', 'International Law', 'Family Law'],
                    2 => ['Corporate Law', 'Labor Law', 'Intellectual Property', 'Legal Ethics'],
                ],
                4 => [
                    1 => ['Civil Procedure', 'Administrative Law', 'International Law', 'Family Law'],
                    2 => ['Corporate Law', 'Labor Law', 'Intellectual Property', 'Legal Ethics'],
                ],
            ],
        ];

        foreach ($coursesData as $departmentName => $years) {
            if ($departmentName === 'Administration') {
                continue;
            }
            $departmentId = $departments[$departmentName];
            $department = $departments[$departmentName] ?? null;
            if ($department) {

                foreach ($years as $year => $semesters) {
                    foreach ($semesters as $semesterNumber => $courses) {
                        $semesterId = $semesterNumber === 1 ? $springSemester->id : $fallSemester->id;
                        foreach ($courses as $courseName) {
                            // Generate unique code
                            do {
                                $code = strtoupper(substr($departmentName, 0, 3)) . str_pad(rand(101, 499), 3, '0', STR_PAD_LEFT);
                                $exists = Course::where('code', $code)->exists();
                            } while ($exists);

                            Course::updateOrCreate(
                                ['code' => $code],
                                [
                                    'name' => $courseName,
                                    'semester_id' => $semesterId,
                                    'department_id' => $department->id,
                                    'year' => $year,
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
