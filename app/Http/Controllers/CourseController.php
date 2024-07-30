<!-- <?php

// namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function registerForCourse(Request $request)
    {
        // $request->validate([
        //     'course_id' => 'required|exists:courses,id',
        // ]);

        // $user = auth()->user();
        // $course = Course::findOrFail($request->course_id);

        // Check if the course is appropriate for the student's department, year, and semester
        // if ($course->department_id == $user->department_id &&
        //     $course->year == $user->year_of_study) {
        //     $user->courses()->attach($course->id);

        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Course registered successfully.',
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Course not available for your department or year of study.',
        //     ], 400);
        // }
    }
} 
