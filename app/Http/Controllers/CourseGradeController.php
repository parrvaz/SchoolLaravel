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
            'grade_id' => 'required|exists:grades,id',
        ]);

        CourseGrade::create([
            "name"=>$validated['name'],
            "grade_id"=>$validated['grade_id'],
            "user_grade_id"=> $request->userGrade->id,
        ]);

        return $this->successMessage();
    }

    public function update(Request $request,$userGrade,CourseGrade $courseGrade){
        $validated = $request->validate([
            'name' => 'required|min:2|max:255',
            'grade_id' => 'required|exists:grades,id',
        ]);

        $courseGrade->update([
            "name"=>$validated['name'],
            "grade_id"=>$validated['grade_id'],
        ]);

        return $this->successMessage();
    }

    public function show(Request $request){
        return new CourseGradeCollection($request->userGrade->courseGrades);
    }

    public function delete($userGrade,CourseGrade $courseGrade){
        $courseGrade->delete();
        return $this->successMessage();
    }
}
