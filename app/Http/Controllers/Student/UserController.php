<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Semester;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->student_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $department_name = $user->department;
        $year_of_study = $user->year_of_study;

        $request->validate([
            'semester_id' => 'required|integer',
        ]);

        $courses = Course::whereHas('department', function ($query) use ($department_name) {
            $query->where('name', $department_name);
        })
        ->where('year', $year_of_study)
        ->where('semester_id', $request->input('semester_id'))
        ->get();

        return response()->json($courses);
    }


    public function registerCourse(Request $request)
{
    $validator = Validator::make($request->all(), [
        'course_codes' => 'required|array',
        'course_codes.*' => 'exists:courses,code',
        'semester' => 'required|integer',
        'year' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $student = $request->user();

    // Get course IDs based on the course codes
    $courseIds = Course::whereIn('code', $request->input('course_codes'))
        ->where('year', $request->input('year'))
        ->where('semester_id', $request->input('semester'))
        ->pluck('id')
        ->toArray();

    $existingCourseIds = $student->courses()
        ->wherePivot('semester', $request->input('semester'))
        ->wherePivot('year', $request->input('year'))
        ->pluck('course_id')
        ->toArray();

    $newCourseIds = array_diff($courseIds, $existingCourseIds);

    if (empty($newCourseIds)) {
        return response()->json(['message' => 'You have already registered for these courses.'], 400);
    }

    $student->courses()->attach($newCourseIds, [
        'semester' => $request->input('semester'),
        'year' => $request->input('year'),
    ]);

    return response()->json(['success' => 'Courses registered successfully.']);
}

    

    // public function submitAssignment(Request $request, Assignment $assignment)
    // {
    //     $validatedData = $request->validate([
    //         'file_path' => 'required|string|max:255',
    //     ]);

    //     $submission = Submission::create([
    //         'assignment_id' => $assignment->id,
    //         'student_id' => auth()->id(),
    //         'file_path' => $validatedData['file_path'],
    //     ]);

    //     return response()->json(['message' => 'Assignment submitted successfully', 'submission' => $submission]);
    // }


    // public function viewAssignments()
    // {
    //     $user = auth()->user();
    //     $courses = $user->courses;

    //     $assignments = Assignment::whereIn('course_id', $courses->pluck('id'))->get();

    //     return response()->json(['assignments' => $assignments]);
    // }


    // public function viewExams()
    // {
    //     $user = auth()->user();
    //     $courses = $user->courses;

    //     $exams = Exam::whereIn('course_id', $courses->pluck('id'))->get();

    //     return response()->json(['exams' => $exams]);
    // }

}
