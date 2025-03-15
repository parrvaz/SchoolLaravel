<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course\CourseGradeCollection;
use App\Models\ClassCourseTeacher;
use App\Models\Course;
use App\Models\CourseField;
use App\Models\CourseGrade;
use App\Models\Schedule;
use Illuminate\Http\Request;

class CourseGradeController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|min:1|max:255',
        ]);

       $course = Course::create([
            "name"=>$validated['name'],
            "title"=>$validated['name'],
            "grade_id"=> $request->schoolGrade->grade_id ,
            "school_grade_id"=> $request->schoolGrade->id,
        ]);


        CourseField::create([
            "course_id"=>$course->id
        ]);

        return $this->successMessage();
    }

    public function update(Request $request,$schoolGrade,Course $course){
        if ($course->school_grade_id == $request->schoolGrade->id){

        $validated = $request->validate([
            'name' => 'required|min:1|max:255',
        ]);

            $course->update([
            "name"=>$validated['name'],
            "title"=>$validated['name'],
        ]);

        return $this->successMessage();
        }
        return  $this->error();
    }

    public function show(Request $request){
        return new CourseGradeCollection(Course::where("school_grade_id",$request->schoolGrade->id)->get());
    }

    public function delete(Request $request,$schoolGrade,Course $course){
        if ($course->school_grade_id == $request->schoolGrade->id){
            $course->schedules()->delete();
            $course->classTeachers()->delete();
            $course->delete();
            return $this->successMessage();
        }
        return  $this->error("dontExist");
    }
}
