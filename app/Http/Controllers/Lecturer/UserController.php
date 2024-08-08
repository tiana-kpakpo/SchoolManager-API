<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createAssignment(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $assignment = Assignment::create($validatedData);

        return response()->json(['message' => 'Assignment created successfully', 'assignment' => $assignment]);
    }

    public function createExam(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $exam = Exam::create($validatedData);

        return response()->json(['message' => 'Exam created successfully', 'exam' => $exam]);
    }

    public function gradeSubmission(Request $request, Submission $submission)
    {
        $validatedData = $request->validate([
            'grade' => 'required|integer|min:0|max:100',
        ]);

        $grade = Grade::updateOrCreate(
            ['submission_id' => $submission->id],
            ['grade' => $validatedData['grade']]
        );

        return response()->json(['message' => 'Submission graded successfully', 'grade' => $grade]);
    }

    public function showLecturerCourses($lecturer_id)
    {
        $lecturer = User::find($lecturer_id);

        if (!$lecturer) {
            return response()->json(['error' => 'Lecturer not found.'], 404);
        }

        $assignedCourses = Course::where('lecturer_id', $lecturer_id)->get();

        return response()->json([
            'lecturer' => $lecturer,
            'assigned_courses' => $assignedCourses
        ]);
    }

}
