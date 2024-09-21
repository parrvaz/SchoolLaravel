<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CourseValidation;
use App\Http\Resources\Course\AssignCreateResource;
use App\Http\Resources\Course\CourseClassroomCollection;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Grade\ExamCreateResource;
use App\Models\ClassCourseTeacher;
use App\Models\Course;
use App\Models\CourseGrade;
use App\Models\UserGrade;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * store class and teacher of courses
     */


   public function store(Request $request, CourseValidation $validation){
       //delete all
       ClassCourseTeacher::whereHas('classroom', function($query) use($request) {
           return $query->where('user_grade_id', $request->userGrade->id);})->delete();

       //create new
        ClassCourseTeacher::insert($validation->validated()['list']);

        return $this->successMessage();
   }

   public function show(Request $request){
       return new CourseCollection(
           Course::where('grade_id',$request->userGrade->grade_id)
           ->where('user_grade_id',$request->userGrade->id)
               ->orWhere('user_grade_id',null)
           ->get());
   }

   public function showSingle($userGrade,Course $course){
    return new CourseResource($course);
   }

   public function showClassroom(Request $request){
       return new CourseClassroomCollection( ClassCourseTeacher::whereHas('classroom', function($query) use($request) {
           return $query->where('user_grade_id', $request->userGrade->id);})->get());
   }

   public function assignCreate(Request $request){
       return new AssignCreateResource($request->userGrade);

   }
}
