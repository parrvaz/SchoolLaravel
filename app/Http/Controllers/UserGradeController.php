<?php

namespace App\Http\Controllers;

use App\Http\Requests\Grades\UserGradesValidation;
use App\Http\Resources\Grades\UserGradeResource;
use App\Models\UserGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserGradeController extends Controller
{
    public function store(UserGradesValidation $validation){
        $grade = UserGrade::create([
            'user_id'=>auth()->user()->id,
            'grade_id'=>$validation->grade_id,
            'title'=>$validation->title,
        ]);
        return new UserGradeResource($grade);
    }
}
