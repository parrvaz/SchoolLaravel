<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateValidation;
use App\Models\SchoolGrade;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(UserUpdateValidation $validation){
        $user =  auth()->user();
        $role =$user->role;
        switch ($role){
            case config("constant.roles.assistant"):
            case config("constant.roles.teacher"):
                $teacher = $user->teacher;




                break;
            case config("constant.roles.manager"):
//                $grades = SchoolGrade::where('school_id',$school->id)->get();
                break;
        }

        return $this->successMessage();
    }
}
