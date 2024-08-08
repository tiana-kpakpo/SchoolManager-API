<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Fee;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'age' => 'required|string',
            'nationality' => 'required|string',
            'department' => 'nullable|string',
            'qualification' => 'nullable|string',
            'year_of_study' => 'nullable|integer|min:1|max:4',
            'guardian_contact' => 'nullable|string',
            'role' => 'required|string|in:student,lecturer,admin',
        ]);

        // Default values
        $input['date_of_admission'] = $input['date_of_admission'] ?? Carbon::now()->format('Y-m-d');

        if ($request->input('role') === 'student') {
            $input['student_id'] = $this->generateStudentId();
            $input['year_of_study'] = $input['year_of_study'] ?? 1;
            $input['qualification'] = null;

            if ($request->has('department')) {
                $fee = Fee::where('department', $request->department)->first();
                if ($fee) {
                    $input['outstanding_fees'] = $fee->amount;
                }
                
            }
    
        } elseif ($request ->input('role')==='lecturer'){
            $input['lecturer_id'] = $this->generateLecturerId();
            $input['outstanding_fees'] = null;
            $input['year_of_study'] = null;
            // $input['department'] = $input['department'] ?? null; 

        }  

        $input['is_admin'] = $request->input('role') === 'admin';

        // Create user
        $user = User::create(array_merge(
            $input, 
            ['password' => Hash::make($input['password'])]
        )); 
        
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

    private function generateStudentId()
    {
        return 'STD' . strtoupper(uniqid());
    }

    private function generateLecturerId()
    {
        return 'LCT' . strtoupper(uniqid());
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



public function lecturerCourses(Request $request)
{
    // Validate the request
    $validatedData = $request->validate([
        'lecturer_id' => 'required|exists:users,id',
        'course_ids' => 'required|array',
        'course_ids.*' => 'exists:courses,id',
    ]);

    $lecturerId = $validatedData['lecturer_id'];
    $courseIds = $validatedData['course_ids'];

    // Find the lecturer
    $lecturer = User::find($lecturerId);

    if (!$lecturer) {
        return response()->json(['error' => 'Lecturer not found.'], 404);
    }

    // Assign courses to the lecturer
    foreach ($courseIds as $courseId) {
        $course = Course::find($courseId);
        if ($course) {
            $course->lecturer_id = $lecturer->id;
            $course->save();
        }
    }

    // Fetch the assigned courses
    $assignedCourses = Course::where('lecturer_id', $lecturerId)->get();

    return response()->json([
        'success' => 'Courses assigned to lecturer successfully.',
        'assigned_courses' => $assignedCourses
    ]);
}

public function getAllLecturers()
{
    $lecturers = User::where('is_admin', false)
                      ->whereNotNull('lecturer_id')
                      ->get();

    return response()->json([
        'lecturers' => $lecturers
    ]);
}



}
