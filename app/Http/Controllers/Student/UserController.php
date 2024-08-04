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
        $request->validate([
            'department_name' => 'required|string',
            'year' => 'required|integer',
            'semester_id' => 'required|integer',
        ]);

        $courses = Course::whereHas('department', function ($query) use ($request) {
            $query->where('name', $request->input('department_name'));
        })
        ->where('year', $request->input('year'))
        ->where('semester_id', $request->input('semester_id'))
        ->get();

        return response()->json($courses);
    }


    public function registerCourse(Request $request)
    {
         
         $validator = Validator::make($request->all(), [
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $student = $request->user();
    
        $student->courses()->sync($request->input('course_ids'));

    
        return response()->json(['success' => 'Course registered successfully.']);
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $validatedData = $request->validate([
            'file_path' => 'required|string|max:255',
        ]);

        $submission = Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => auth()->id(),
            'file_path' => $validatedData['file_path'],
        ]);

        return response()->json(['message' => 'Assignment submitted successfully', 'submission' => $submission]);
    }

    public function viewAssignments()
    {
        $user = auth()->user();
        $courses = $user->courses;

        $assignments = Assignment::whereIn('course_id', $courses->pluck('id'))->get();

        return response()->json(['assignments' => $assignments]);
    }

    public function viewExams()
    {
        $user = auth()->user();
        $courses = $user->courses;

        $exams = Exam::whereIn('course_id', $courses->pluck('id'))->get();

        return response()->json(['exams' => $exams]);
    }

}
