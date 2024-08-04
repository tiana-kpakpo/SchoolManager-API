<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\Lecturer\UserController as LecturerUserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Student\UserController as StudentUserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('password/change', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Admin routes
    Route::prefix('admin')->middleware('is_admin')->group(function () {
        Route::post('register', [UserController::class, 'register']);
        Route::post('courses', [UserController::class, 'storeCourse']);


        // Fees routes
        Route::get('/fees', [FeesController::class, 'index']);
        Route::get('/fees/{id}', [FeesController::class, 'showStdFees']);
        Route::post('/fees', [FeesController::class, 'store']);
        Route::put('/fees/{id}', [FeesController::class, 'update']);
        Route::delete('/fees/{id}', [FeesController::class, 'destroy']);

        // Payment routes
        Route::get('outstanding-fees/{student_id}', [PaymentController::class, 'index']);
        Route::post('payments', [PaymentController::class, 'makePayment']);
        // Route::put('payments/{id}', [PaymentController::class, 'update']);
        // Route::delete('payments/{id}', [PaymentController::class, 'destroy']);
        // Route::get('payments/user/{userId}', [PaymentController::class, 'showByUser']);
    });


    //Lecturer routes
    Route::prefix('lecturer')->middleware('is_lecturer')->group(function () {
        Route::post('submit-grade', [LecturerUserController::class, 'gradeSubmission']);
        Route::post('assignments', [LecturerUserController::class, 'createAssignment']);
        Route::post('exams', [LecturerUserController::class, 'createExam']);

    });

    // Student routes
    Route::prefix('student')->middleware('is_student')->group(function () {
        Route::get('fees/{user_id}', [FeesController::class, 'showMyFees']);
        Route::get('payments', [PaymentController::class, 'index']);
        Route::get('fees/outstanding/{id}', [FeesController::class, 'showOutstandingFees']); 
        Route::get('payments/user/{userId}', [PaymentController::class, 'showByUser']);

        Route::post('/courses', [StudentUserController::class, 'index']);
        Route::post('/register-courses', [StudentUserController::class, 'registerCourse']);

        Route::get('assignments', [AssignmentController::class, 'index']);
        Route::get('assignments/{assignment}', [AssignmentController::class, 'show']);
        Route::get('assignments/{assignment}/submit', [AssignmentController::class, 'submit']);
        Route::get('exam', [ExamController::class, 'index']);
        Route::get('exam/{id}', [ExamController::class, 'index']);
        Route::get('exam/{id}/submit', [ExamController::class, 'index']);
    });


});
