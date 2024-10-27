<?php

use App\Http\Controllers\AbsentController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\BellController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseGradeController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\TeacherController;
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
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:api');
Route::post('log', [AuthenticationController::class, 'log'])->name('log');

Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthenticationController::class, 'user']);
    Route::post('changePassword', [AuthenticationController::class, 'changePassword']);
    Route::post('user/update', [AuthenticationController::class, 'update']);


    Route::post('/studyPlanStore', [StudyController::class, 'studyPlanStore']);


    Route::post('/operation', [GradeController::class, 'operation']);



    //UserGrades Api
    Route::prefix("grades")->group(function () {
        Route::post('/store', [UserGradeController::class, 'store']);
        Route::post('/update/{user_grade}', [UserGradeController::class, 'update']);
        Route::get('/show', [UserGradeController::class, 'show']);
        Route::post('/delete/{user_grade}', [UserGradeController::class, 'delete']);
        Route::get('/items', [MenuItemController::class, 'show']);

    });

    Route::prefix('{userGrade}')->middleware("findUserGrade")->group(function () {

        Route::middleware('role:manager')->post('/update', [UserGradeController::class, 'updateCode']);
        Route::middleware('role:manager')->post('/delete', [UserGradeController::class, 'deleteCode']);

        Route::middleware('role:general')->get('/dashboard', [GradeController::class, 'dashboard']);

        //Field Api
        Route::prefix("fields")->group(function () {
            Route::get('/show', [FieldController::class, 'show']);
        });

        //Classroom Api
        Route::prefix("classrooms")->group(function () {
            Route::middleware('role:assistant')->post('/store', [ClassroomController::class, 'store']);
            Route::middleware('role:assistant')->post('/update/{classroom}', [ClassroomController::class, 'update']);
            Route::middleware('role:teacher')->get('/show', [ClassroomController::class, 'show']);
            Route::middleware('role:teacher')->get('/show/{classroom}', [ClassroomController::class, 'showSingle']);
            Route::middleware('role:assistant')->post('/delete/{classroom}', [ClassroomController::class, 'delete']);

            Route::middleware('role:teacher')->get('/list', [ClassroomController::class, 'list']);
        });

        //Students Api
        Route::prefix("students")->group(function () {
            Route::middleware('role:assistant')->post('/store', [StudentController::class, 'store']);
            Route::middleware('role:assistant')->post('/import', [StudentController::class, 'import']);
            Route::middleware('role:general')->get('/sampleExcel', [StudentController::class, 'sampleExcel']);
            Route::middleware('role:assistant')->post('/update/{student}', [StudentController::class, 'update']);
            Route::middleware('role:teacher')->get('/show', [StudentController::class, 'show']);
            Route::middleware('role:general')->get('/show/{student}', [StudentController::class, 'showSingle']);
            Route::middleware('role:assistant')->post('/delete/{student}', [StudentController::class, 'delete']);
        });

        //Teachers Api
        Route::prefix("teachers")->group(function () {
            Route::middleware('role:assistant')->post('/store', [TeacherController::class, 'store']);
            Route::middleware('role:assistant')->post('/update/{teacher}', [TeacherController::class, 'update']);
            Route::middleware('role:general')->get('/show', [TeacherController::class, 'show']);
            Route::middleware('role:assistant')->get('/show/{teacher}', [TeacherController::class, 'showSingle']);
            Route::middleware('role:assistant')->post('/delete/{teacher}', [TeacherController::class, 'delete']);
        });

        //Course Api
        Route::prefix("courses")->group(function () {
            Route::middleware('role:assistant')->post('/store', [CourseController::class, 'store']);
            Route::middleware('role:general')->get('/show', [CourseController::class, 'show']);
            Route::middleware('role:general')->get('/show/{course}', [CourseController::class, 'showSingle']);
            Route::middleware('role:general')->get('/classroom/show', [CourseController::class, 'showClassroom']);


            Route::middleware('role:general')->get('/assign/create', [CourseController::class, 'assignCreate']);
            Route::middleware('role:assistant')->post('/delete/{course}', [CourseGradeController::class, 'delete']);
            Route::middleware('role:assistant')->post('/update/{course}', [CourseGradeController::class, 'update']);

        });

        //New Course
        Route::prefix("/school/courses")->group(function () {
            Route::middleware('role:assistant')->post('/store', [CourseGradeController::class, 'store']);
            Route::middleware('role:assistant')->get('/show', [CourseGradeController::class, 'show']);

        });

        //Exam Api
        Route::prefix("exams")->group(function () {
            Route::middleware('role:teacher')->post('/store', [ExamController::class, 'store']);
            Route::middleware('role:general')->get('/show', [ExamController::class, 'show']);
            Route::middleware('role:general')->get('/scores', [ExamController::class, 'scores']);
            Route::middleware('role:general')->get('/show/{exam}', [ExamController::class, 'showSingle']);
            Route::middleware('role:general')->get('/excel/{exam}', [ExamController::class, 'excel']);
            Route::middleware('role:teacher')->post('/update/{exam}', [ExamController::class, 'update']);
            Route::middleware('role:teacher')->post('/delete/{exam}', [ExamController::class, 'delete']);
        });

        //Bell Api
        Route::prefix("bells")->group(function () {
            Route::middleware('role:manager')->post('/store', [BellController::class, 'store']);
            Route::middleware('role:teacher')->get('/show', [BellController::class, 'show']);
            Route::middleware('role:manager')->post('/update', [BellController::class, 'update']);
            Route::middleware('role:manager')->post('/delete/{bell}', [BellController::class, 'delete']);
        });

        //Schedule
        Route::prefix("schedules")->group(function () {
            Route::middleware('role:assistant')->post('/store/{classroom}', [ScheduleController::class, 'store']);
            Route::middleware('role:general')->get('/show', [ScheduleController::class, 'show']);
            Route::middleware('role:general')->get('/show/{classroom}', [ScheduleController::class, 'showSingle']);
            Route::middleware('role:assistant')->post('/update/{classroom}', [ScheduleController::class, 'update']);
            Route::middleware('role:assistant')->post('/delete/{classroom}', [ScheduleController::class, 'delete']);
        });


        //Absent Api
        Route::prefix("absents")->group(function () {
            Route::middleware('role:teacher')->post('/store', [AbsentController::class, 'store']);
            Route::middleware('role:teacher')->get('/show', [AbsentController::class, 'show']);
            Route::middleware('role:teacher')->get('/teachersMiss', [AbsentController::class, 'teachersMiss']);
            Route::middleware('role:teacher')->post('/update/{absent}', [AbsentController::class, 'update']);
            Route::middleware('role:teacher')->post('/delete/{absent}', [AbsentController::class, 'delete']);
        });

        //Message
        Route::prefix("messages")->group(function () {
            Route::middleware('role:general')->post('/send', [MessageController::class, 'send']);
            Route::middleware('role:general')->post('/markAsRead/{messageRecipient}', [MessageController::class, 'markAsRead']);
            Route::middleware('role:general')->get('/inbox', [MessageController::class, 'inbox']);
            Route::middleware('role:general')->get('/sentMessages', [MessageController::class, 'sentMessages']);

        });


        //Plan Api
        Route::prefix("plans")->group(function () {
            Route::middleware('role:assistant')->post('/store', [PlanController::class, 'store']);
            Route::middleware('role:assistant')->post('/assign', [PlanController::class, 'assign']);
            Route::middleware('role:assistant')->get('/show', [PlanController::class, 'show']);
            Route::middleware('role:student')->get('/show/{plan}', [PlanController::class, 'showSingle']);
            Route::middleware('role:assistant')->post('/update/{planModel}', [PlanController::class, 'update']);
            Route::middleware('role:assistant')->post('/delete/{plan}', [PlanController::class, 'delete']);
        });

        //Study Api
        Route::prefix("studies")->group(function () {
            Route::middleware('role:student')->get('/show', [StudyController::class, 'show']);
            Route::middleware('role:assistant')->get('/show/{student}', [StudyController::class, 'showStudent']);
            Route::middleware('role:student')->post('/store', [StudyController::class, 'store']);
            Route::middleware('role:assistant')->post('/store/{student}', [StudyController::class, 'storeStudent']);
            Route::middleware('role:student')->post('/delete/{study}', [StudyController::class, 'delete']);
        });


        //Report Api
        Route::prefix("reports")->group(function () {
            Route::middleware('role:general')->get('/card', [ReportController::class, 'card']);
            Route::middleware('role:general')->get('/card/excel', [ReportController::class, 'cardExcel']);
            Route::middleware('role:general')->get('/progress', [ReportController::class, 'progress']);
            Route::middleware('role:assistant')->get('/absents', [ReportController::class, 'absents']);
        });





    });

});
