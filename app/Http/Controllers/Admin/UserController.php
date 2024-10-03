<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Fee;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;



class UserController extends Controller
{


    public function register(Request $request)
    {
        // Validate input
        $input = $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|string',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'age' => 'required|string',
            'nationality' => 'required|string',
            'qualification' => 'nullable|string',
            'year_of_study' => 'nullable|integer|min:1|max:4',
            'guardian_contact' => 'nullable|string',
            'role' => 'required|string|in:student,lecturer,admin',
            'faculty' => 'required|string',
            'course_codes' => 'nullable|array',
            'course_codes.*' => 'exists:courses,code',
            'semester' => 'nullable|integer',
            'year' => 'nullable|integer',
        ]);

        // Default values
        $input['date_of_admission'] = $input['date_of_admission'] ?? Carbon::now()->format('Y-m-d');

        if ($request->input('role') === 'student') {
            $input['student_id'] = $this->generateStudentId();
            $input['email'] = $this->generateEmail($input['student_id'], 'student');
            $input['year_of_study'] = $input['year_of_study'] ?? 1;
            $input['qualification'] = null;

            if ($request->has('department')) {
                $department = Department::where('name', $request->input('department'))->first();

                if (!$department) {
                    return response()->json(['message' => 'invalide department name'], 400);
                }

                $input['department'] = $department->name;

                $fee = Fee::where('department', $request->input('department'))->first();
                if ($fee) {
                    $input['outstanding_fees'] = $fee->amount;
                } else {
                    $input['outstanding_fees'] = 2500;
                }
            } else {
                return response()->json(['message' => 'Department name is required for students'], 400);
            }
        } elseif ($request->input('role') === 'lecturer') {
            $input['lecturer_id'] = $this->generateLecturerId();
            $input['email'] = $this->generateEmail($input['lecturer_id'], 'lecturer');
            $input['year_of_study'] = null;
            $input['outstanding_fees'] = 0.00;


            if ($request->has('faculty')) {
                $faculty = Faculty::where('name', $request->faculty)->first();
                if (!$faculty) {
                    return response()->json(['error' => 'Faculty not found.'], 404);
                }

                $input['faculty_id'] = $faculty->id;
            }
        } elseif ($input['is_admin'] = $request->input('role') === 'admin') {
            $input['department'] = 'Administration';
            $input['year_of_study'] = null;
        }

        // Create user
        $user = User::create(array_merge(
            $input,
            ['password' => Hash::make($input['password'])]
        ));

        // Assign courses if the user is a lecturer
        if ($request->input('role') === 'lecturer' && $request->has('course_codes')) {
            $this->lecturerCourses($user->id, $request->input('course_codes'), $request->input('semester'), $request->input('year'));
        }


        $role = Role::where('name', $request->input('role'))->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully.',
            'user' => $user,
        ], 201);
    }


    private function generateLecturerId()
    {
        return 'LCT' . strtoupper(uniqid());
    }


    private function generateStudentId()
    {
        return 'STD' . strtoupper(uniqid());
    }

    protected function generateEmail($id, $type)
    {
        $domain = 'st.uew.edu.gh';

        return Str::lower($id . '@' . $domain);
    }


    public function getAllLecturers()
    {
        $lecturers = User::where('is_admin', false)
            ->whereNotNull('lecturer_id')
            ->paginate(10);

        return response()->json([
            'lecturers' => $lecturers
        ]);
    }

    public function getAllStudents()
    {
        $students = User::where('is_admin', false)
            ->whereNotNull('student_id')
            ->paginate(2);

        return response()->json([
            'students' => $students
        ]);
    }

    // private function lecturerCourses($lecturerId, array $courseCodes, $semester, $year)
    // {
    //     // Find courses by their codes
    //     $courses = Course::whereIn('code', $courseCodes)->get();

    //     if ($courses->isEmpty()) {
    //         return response()->json(['error' => 'No valid courses found.'], 404);
    //     }


    //     foreach ($courses as $course) {
    //         $existingCourse = Course::find($course->id);

    //         if ($existingCourse) {
    //             if ($existingCourse->lecturer_id && $existingCourse->lecturer_id !== $lecturerId) {
    //                 return response()->json([
    //                     'error' => "Course {$course->name} ({$course->code}) is already assigned to another lecturer."
    //                 ], 400);
    //             }

    //             // Check if the semester and year match
    //             if ($existingCourse->semester_id !== $semester || $existingCourse->year !== $year) {
    //                 return response()->json([
    //                     'error' => "Course {$course->name} ({$course->code}) is not available in semester {$semester} and year {$year}."
    //                 ], 400);
    //             }
    //         } else {
    //             return response()->json(['error' => "Course {$course->code} not found."], 404);
    //         }
    //     }

    //     foreach ($courses as $course) {
    //         $course->lecturer_id = $lecturerId;
    //         $course->save();
    //     }

    //     $pivotData = $courses->mapWithKeys(function ($course) use ($lecturerId, $semester, $year) {
    //         return [$course->id => ['lecturer_id' => $lecturerId, 'semester' => $semester, 'year' => $year]];
    //     })->toArray();

    //     // Log the pivot data
    //     Log::info('Pivot Data:', $pivotData);

    //     $lecturer = User::find($lecturerId);
    //     $lecturer->lecturerCourses()->syncWithoutDetaching($pivotData);

    //     // Log the final pivot table entries
    //     Log::info('Lecturer Courses:', $lecturer->lecturerCourses()->get()->toArray());

    //     return response()->json(['message' => 'Courses assigned successfully.', 'courses' => $lecturer->courses], 200);
    // }

//     private function lecturerCourses($lecturerId, array $courseCodes, $semester, $year)
// {
//     // Find courses by their codes
//     $courses = Course::whereIn('code', $courseCodes)->get();

//     if ($courses->isEmpty()) {
//         return response()->json(['error' => 'No valid courses found.'], 404);
//     }

//     // Prepare the pivot data for attaching the courses to the lecturer
//     $pivotData = [];
//     foreach ($courses as $course) {
//         // Ensure that the course is not already assigned to another lecturer for the given semester and year
//         $existingAssignment = DB::table('course_lecturer')
//             ->where('course_id', $course->id)
//             ->where('semester', $semester)
//             ->where('year', $year)
//             ->where('lecturer_id', '!=', $lecturerId)
//             ->first();

//         if ($existingAssignment) {
//             return response()->json([
//                 'error' => "Course {$course->name} ({$course->code}) is already assigned to another lecturer in semester {$semester} and year {$year}."
//             ], 400);
//         }

//         // Prepare the pivot data for this course
//         $pivotData[$course->id] = ['semester' => $semester, 'year' => $year];
//     }

//     // Attach or sync the courses to the lecturer
//     $lecturer = User::findOrFail($lecturerId);
//     $lecturer->lecturerCourses()->syncWithoutDetaching($pivotData);

//     // Return the lecturer's assigned courses
//     return response()->json([
//         'message' => 'Courses assigned successfully.',
//         'courses' => $lecturer->lecturerCourses()->get(),
//     ], 200);
// }

// private function lecturerCourses($lecturerId, array $courseCodes)
// {
//     // Find courses by their codes
//     $courses = Course::whereIn('code', $courseCodes)->get();

//     if ($courses->isEmpty()) {
//         return response()->json(['error' => 'No valid courses found.'], 404);
//     }

//     $pivotData = [];

//     foreach ($courses as $course) {
//         // Use the course's specific semester and year
//         $existingAssignment = DB::table('course_lecturer')
//             ->where('course_id', $course->id)
//             ->where('lecturer_id', '!=', $lecturerId)
//             ->first();

//         if ($existingAssignment) {
//             return response()->json([
//                 'error' => "Course {$course->name} ({$course->code}) is already assigned to another lecturer in semester {$course->semester_id} and year {$course->year}."
//             ], 400);
//         }

//         // Prepare the pivot data with the correct semester and year for each course
//         $pivotData[$course->id] = ['semester' => $course->semester_id, 'year' => $course->year];
//     }

//     // Attach or sync the courses to the lecturer
//     $lecturer = User::findOrFail($lecturerId);
//     $lecturer->lecturerCourses()->syncWithoutDetaching($pivotData);

//     // Return the lecturer's assigned courses
//     return response()->json([
//         'message' => 'Courses assigned successfully.',
//         'courses' => $lecturer->lecturerCourses()->get(),
//     ], 200);
// }

private function lecturerCourses($lecturerId, array $courseCodes)
{
    // Find courses by their codes
    $courses = Course::whereIn('code', $courseCodes)->get();

    if ($courses->isEmpty()) {
        return response()->json(['error' => 'No valid courses found.'], 404);
    }

    // Prepare the pivot data for attaching the courses to the lecturer
    $pivotData = [];
    foreach ($courses as $course) {
        // Check if the course is already assigned to another lecturer for the given semester and year
        $existingAssignment = DB::table('course_lecturer')
            ->where('course_id', $course->id)
            ->where('semester', $course->semester_id) // Use the course's semester
            ->where('year', $course->year) // Use the course's year
            ->where('lecturer_id', '!=', $lecturerId)
            ->first();

        if ($existingAssignment) {
            return response()->json([
                'error' => "Course {$course->name} ({$course->code}) is already assigned to another lecturer in semester {$course->semester_id} and year {$course->year}."
            ], 400);
        }

        // Prepare the pivot data for this course
        $pivotData[$course->id] = ['semester' => $course->semester_id, 'year' => $course->year];
    }

    // Attach or sync the courses to the lecturer
    $lecturer = User::findOrFail($lecturerId);
    $lecturer->lecturerCourses()->syncWithoutDetaching($pivotData);

    // Return the lecturer's assigned courses
    return response()->json([
        'message' => 'Courses assigned successfully.',
        'courses' => $lecturer->lecturerCourses()->get(),
    ], 200);
}




    public function coursesForLecturer(Request $request, $lecturerId)
    {
        $input = $request->validate([
            'course_code' => 'required|array',
            'course_codes.*' => 'exists:courses,code',
            'semester' => 'required|integer',
            'year' => 'required|integer'
        ]);

        $this->lecturerCourses($lecturerId, $input['course_codes'], $input['semester'], $input['year']);

        return response()->json([
            'status' => 'success',
            'message' => 'Courses assigned to lecturer successfully.'
        ], 200);
    }

    public function updateUser(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $input = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'age' => 'required|string',
            'nationality' => 'required|string',
            'qualification' => 'nullable|string',
            'guardian_contact' => 'nullable|string',
            'faculty' => 'required|string',
            'course_codes' => 'nullable|array',
            'course_codes.*' => 'exists:courses,code',
        ]);
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_code' => 'required|string',
            'semester_id' => 'required|integer|exists:semesters,id',
            'year' => 'required|integer',
            'semester' => 'required|integer',
        ]);

        $department = Department::where('code', $request->input('department_code'))->firstOrFail();

        $course = Course::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'description' => $request->input('description'),
            'department_id' => $department->id,
            'semester_id' => $request->input('semester_id'),
            'year' => $request->input('year'),
            'semester' => $request->input('semester'),
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course,
        ], 201);
    }
}
