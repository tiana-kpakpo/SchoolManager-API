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

class UserController extends Controller
{
    
    public function index(Request $request, Course $course)
    {
        $user = $request->user();

        $currentYear = now()->year;

        // $this->authorize('viewAny', Course::class);

        if ($request->user()->cannot('viewAny', $course)) {
            abort(403);
        }

        $currentSemester = Semester::where('year', $currentYear)
            ->orderBy('id', 'desc')
            ->first();

            if (!$currentSemester) {
                return response()->json(['message' => 'No semester found for the current year.'], 404);
            }
        
            $courses = Course::where('department_id', $user->department_id)
                ->where('semester_id', $currentSemester->id)
                ->where('year', $user->year_of_study)
                ->get();

        return response()->json($courses);
    }

    public function register(Request $request, Course $course)
    {
        $user = Auth::user();

        // $this->authorize('viewAny', Course::class);

        if ($request->user()->cannot('viewAny', $course)) {
            abort(403);
        }

        $currentSemester = Semester::where('year', now()->year)
        ->orderBy('id', 'desc')
        ->first();

    if (!$currentSemester) {
        return response()->json(['message' => 'Current semester not found.'], 404);
    }


        $request->validate([
            'courses' => 'required|array',
            'courses.*.code' => 'nullable|string|exists:courses,code',
            'courses.*.name' => 'nullable|string|exists:courses,name',
        ]);

        $courseIds = [];
        foreach ($request->courses as $course) {
            if (isset($course['code'])) {
                $courseModel = Course::where('code', $course['code'])
                    ->where('department_id', $user->department_id)
                    ->where('year', $user->year_of_study)
                    ->where('semester_id', $currentSemester->id)
                    ->first();
            } elseif (isset($course['name'])) {
                $courseModel = Course::where('name', $course['name'])
                    ->where('department_id', $user->department_id)
                    ->where('year', $user->year_of_study)
                    ->where('semester_id', $currentSemester->id)
                    ->first();
            }

            if (isset($courseModel)) {
                $courseIds[] = $courseModel->id;
            }
        }

         // Attach courses to the student
        //  $user->courses()->attach($courseIds);
         $user->courses->syncWithoutDetaching($courseIds);


         return response()->json([
             'status' => 'success',
             'message' => 'Courses registered successfully.',
            //  'courses' => $user->courses()->whereIn('id', $courseIds)->get(),
         ], 201);

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
