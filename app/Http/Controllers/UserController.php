<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateValidation;
use App\Http\Resources\Teacher\TeacherResource;
use App\Models\SchoolGrade;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(UserUpdateValidation $validation){

        $teacher = auth()->user()->teacher;

        //change phone if is changed
        if ($validation->phone != $teacher->phone) {
            $teacher->user->update([
                'phone'=>$validation->phone
            ]);
        }

        //update other values
        $teacher->update([
            'firstName' => $validation->firstName,
            'lastName' => $validation->lastName,
            'nationalId' => $validation->nationalId,
            'degree' => $validation->degree,
            'personalId' => $validation->personalId,
            'isAssistant' => $validation->isAssistant,
            'phone' => $validation->phone,
        ]);

        return $this->successMessage();
    }
}
