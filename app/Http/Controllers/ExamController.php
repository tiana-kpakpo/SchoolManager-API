<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Submission;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    
    public function index()
    {
        $user = auth()->user();
        $exams = Exam::whereHas('courses', function ($query) use ($user) {
            $query->whereIn('id', $user->courses->pluck('id'));
        })->get();

        return response()->json($exams);
    }

    public function show($id)
    {
        $exam = Exam::findOrFail($id);
        return response()->json($exam);
    }


}
