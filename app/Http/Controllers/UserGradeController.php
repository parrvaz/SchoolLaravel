<?php

namespace App\Http\Controllers;

use App\Http\Requests\Grades\UserGradesValidation;
use App\Http\Resources\Grade\UserGradeCollection;
use App\Http\Resources\Grade\UserGradeResource;
use App\Models\UserGrade;
use Illuminate\Support\Str;

class UserGradeController extends Controller
{

    public function store(UserGradesValidation $validation){
        $grade = UserGrade::create([
            'user_id'=>auth()->user()->id,
            'grade_id'=>$validation->grade_id,
            'title'=>$validation->title,
            'code'=> Str::random(30),
        ]);
        return new UserGradeResource($grade);
    }

    public function update(UserGrade $userGrade,UserGradesValidation $validation){
        $userGrade->update([
            'grade_id'=>$validation->grade_id,
            'title'=>$validation->title,
        ]);

        return new UserGradeResource($userGrade);
    }

    public function show(){
        return new UserGradeCollection(UserGrade::where('user_id',auth()->user()->id)->paginate(config('constant.paginate')));
    }

    public function delete(UserGrade $userGrade){
        $userGrade->delete();
        return $this->successMessage();
    }
    
}
