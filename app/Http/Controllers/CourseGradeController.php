<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course\CourseGradeCollection;
use App\Models\Course;
use App\Models\CourseGrade;
use Illuminate\Http\Request;

class CourseGradeController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|min:2|max:255',
        ]);

        Course::create([
            "name"=>$validated['name'],
            "title"=>$validated['name'],
            "grade_id"=> $request->userGrade->grade_id ,
            "user_grade_id"=> $request->userGrade->id,
        ]);

        return $this->successMessage();
    }

    public function update(Request $request,$userGrade,Course $course){
        if ($course->user_grade_id == $request->userGrade->id){

        $validated = $request->validate([
            'name' => 'required|min:2|max:255',
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
        return new CourseGradeCollection(Course::where("user_grade_id",$request->userGrade->id)->get());
    }

    public function delete(Request $request,$userGrade,Course $course){
        if ($course->user_grade_id == $request->userGrade->id){
            $course->delete();
            return $this->successMessage();
        }
        return  $this->error();
    }
}
