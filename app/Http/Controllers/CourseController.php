<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CourseValidation;
use App\Models\ClassCourseTeacher;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * store class and teacher of courses
     */
   public function store(Request $request, CourseValidation $validation){
       //delete all
       ClassCourseTeacher::whereHas('classroom', function($query) use($request) {
           return $query->where('user_grade_id', $request['userGrade']->id);})->delete();

       //create new
        ClassCourseTeacher::insert($validation->validated()['list']);

        return $this->successMessage();
   }

   public function show(Request $request){
      return Course::where('grade_id',$request['userGrade']->grade_id)->paginate(config("constant.bidPaginate"));
   }

   public function showSingle(Course $course){
    return $course;
   }

   public function showClassroom(Request $request){
       return ClassCourseTeacher::whereHas('classroom', function($query) use($request) {
           return $query->where('user_grade_id', $request['userGrade']->id);})->get();
   }
}
