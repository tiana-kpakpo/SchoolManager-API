<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $assignments = Assignment::whereHas('courses', function ($query) use ($user) {
            $query->whereIn('id', $user->courses->pluck('id'));
        })->get();

        return response()->json($assignments);
    }

    public function show($id)
    {
        $assignment = Assignment::findOrFail($id);
        return response()->json($assignment);
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx',
            'comments' => 'nullable|string'
        ]);

        $assignment = Assignment::findOrFail($id);
        $user = Auth::user();
        // Ensure the student is enrolled in the course
        $courseIds = $assignment->courses->pluck('id')->toArray();
        if (!$user->courses()->whereIn('course_id', $courseIds)->exists()) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }
        $filePath = $request->file('file')->store('assignments/submissions');

        // Create the submission
        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $user->id,
            'file_path' => $filePath,
            'comments' => $request->input('comments')
        ]);

        return response()->json(['message' => 'Assignment submitted successfully.'], 201);
    }
}
