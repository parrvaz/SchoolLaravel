<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\UserGradeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthenticationController::class, 'register'])->name('register');
Route::post('login', [AuthenticationController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    //UserGrades Api
    Route::prefix("grades")->group(function () {
        Route::post('/store', [UserGradeController::class, 'store']);
        Route::post('/update/{user_grade}', [UserGradeController::class, 'update']);
        Route::get('/show', [UserGradeController::class, 'show']);
        Route::post('/delete/{user_grade}', [UserGradeController::class, 'delete']);
    });

    Route::middleware("findUserGrade")->group(function () {
        //Classroom Api
        Route::prefix("classrooms")->group(function () {
            Route::post('/store', [ClassroomController::class, 'store']);
            Route::post('/update/{classroom}', [ClassroomController::class, 'update']);
            Route::get('/show', [ClassroomController::class, 'show']);
            Route::get('/show/{classroom}', [ClassroomController::class, 'showSingle']);
            Route::post('/delete/{classroom}', [ClassroomController::class, 'delete']);
        });

        //Students Api
        Route::prefix("students")->group(function () {
            Route::post('/store', [StudentController::class, 'store']);
            Route::post('/update/{student}', [StudentController::class, 'update']);
            Route::get('/show', [StudentController::class, 'show']);
            Route::get('/show/{student}', [StudentController::class, 'showSingle']);
            Route::post('/delete/{student}', [StudentController::class, 'delete']);
        });

        //Teachers Api
        Route::prefix("teachers")->group(function () {
            Route::post('/store', [TeacherController::class, 'store']);
            Route::post('/update/{teacher}', [TeacherController::class, 'update']);
            Route::get('/show', [TeacherController::class, 'show']);
            Route::get('/show/{teacher}', [TeacherController::class, 'showSingle']);
            Route::post('/delete/{teacher}', [TeacherController::class, 'delete']);
        });

        //Course Api
        Route::prefix("courses")->group(function () {
            Route::post('/store', [CourseController::class, 'store']);
            Route::get('/show', [CourseController::class, 'show']);
            Route::get('/show/{course}', [CourseController::class, 'showSingle']);
            Route::get('/classroom/show', [CourseController::class, 'showClassroom']);
        });

        //Exam Api
        Route::prefix("exams")->group(function () {
            Route::post('/store', [ExamController::class, 'store']);
            Route::get('/show', [ExamController::class, 'show']);
            Route::get('/show/{exam}', [ExamController::class, 'showSingle']);
            Route::post('/delete/{exam}', [ExamController::class, 'delete']);
        });
    });

});
