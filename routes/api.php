<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassScoreController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPlanController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\UserGradeController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('register', [AuthenticationController::class, 'register'])->name('register');
Route::post('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('log', [AuthenticationController::class, 'log'])->name('log');

Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthenticationController::class, 'user'])->name('user');



    //UserGrades Api
    Route::prefix("grades")->group(function () {
        Route::post('/store', [UserGradeController::class, 'store']);
        Route::post('/update/{user_grade}', [UserGradeController::class, 'update']);
        Route::get('/show', [UserGradeController::class, 'show']);
        Route::post('/delete/{user_grade}', [UserGradeController::class, 'delete']);
    });

    Route::middleware("findUserGrade")->group(function () {
        Route::get('/dashboard', [GradeController::class, 'dashboard']);

        //Field Api
        Route::prefix("fields")->group(function () {
            Route::get('/show', [FieldController::class, 'show']);
        });

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
            Route::get('/assign/create', [CourseController::class, 'assignCreate']);
        });

        //Exam Api
        Route::prefix("exams")->group(function () {
            Route::post('/store', [ExamController::class, 'store']);
            Route::get('/show', [ExamController::class, 'show']);
            Route::get('/show/{exam}', [ExamController::class, 'showSingle']);
            Route::post('/update/{exam}', [ExamController::class, 'update']);
            Route::post('/delete/{exam}', [ExamController::class, 'delete']);
        });

        //ClassScore Api
        Route::prefix("classScores")->group(function () {
            Route::post('/store', [ClassScoreController::class, 'store']);
            Route::get('/show', [ClassScoreController::class, 'show']);
            Route::get('/show/{classScore}', [ClassScoreController::class, 'showSingle']);
            Route::post('/update/{classScore}', [ClassScoreController::class, 'update']);
            Route::post('/delete/{classScore}', [ClassScoreController::class, 'delete']);
        });

        //Test Api
        Route::prefix("tests")->group(function () {
            Route::post('/store', [TestController::class, 'store']);
            Route::post('/{test}/store', [TestController::class, 'storeStudents']);
            Route::get('/show', [TestController::class, 'show']);
            Route::get('/show/{test}', [TestController::class, 'showSingle']);

            //todo update and delete
//            Route::post('/update/{test}', [TestController::class, 'update']);
//            Route::post('/delete/{test}', [TestController::class, 'delete']);
        });

        Route::prefix("allExams")->group(function () {
            Route::get('/show', [GradeController::class, 'allExamShow']);
            Route::get('/create', [GradeController::class, 'examsCreate']);

        });

        //Plan Api
        Route::prefix("plans")->group(function () {
            Route::post('/store', [PlanController::class, 'store']);
            Route::get('/show', [PlanController::class, 'show']);
            Route::get('/show/{plan}', [PlanController::class, 'showSingle']);
            Route::post('/update/{plan}', [PlanController::class, 'update']);
            Route::post('/delete/{plan}', [PlanController::class, 'delete']);
        });


        //Student Api
        Route::prefix("plans/students")->group(function () {
            Route::post('/store', [StudentPlanController::class, 'store']);
//            Route::get('/show', [StudentPlanController::class, 'show']);
            Route::get('/show/{plan}', [StudentPlanController::class, 'showSingle']);
            Route::post('/update/{plan}', [StudentPlanController::class, 'update']);
            Route::post('/delete/{plan}', [StudentPlanController::class, 'delete']);
        });


        Route::prefix("reports")->group(function () {
            Route::get('/listItems', [ReportController::class, 'listItems']);
            Route::get('/exams/count', [ReportController::class, 'allExamCount']);
            Route::get('/exams/progress', [ReportController::class, 'examProgress']);
            Route::get('/classScores/progress', [ReportController::class, 'classScoreProgress']);

        });
    });

});
