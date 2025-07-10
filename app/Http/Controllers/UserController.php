<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateValidation;
use App\Models\SchoolGrade;


class UserController extends Controller
{
    public function update(UserUpdateValidation $validation){
        return $this->updateUserData($validation);
    }

    public function updateUserData($validation){
        $user =  auth()->user();
        $role =$user->role;
        switch ($role) {
            case config("constant.roles.assistant"):
            case config("constant.roles.teacher"):
                $teacher = auth()->user()->teacher;
                //change phone if is changed
                if ($validation->phone !=null && $validation->phone != $teacher->phone  ) {
                    $teacher->user->update([
                        'phone' => $validation->phone
                    ]);
                }
                //update other values
                $teacher->update([
                    'firstName' => $validation->firstName ?? $teacher->firstName,
                    'lastName' => $validation->lastName  ?? $teacher->lastName,
                    'nationalId' => $validation->nationalId  ?? $teacher->nationalId,
                    'degree' => $validation->degree  ?? $teacher->degree,
                    'personalId' => $validation->personalId  ?? $teacher->personalId,
                    'phone' => $validation->phone  ?? $teacher->phone,
                ]);
                break;
            case config("constant.roles.manager"):
                $user->update([
                    'phone' => $validation->phone ?? $user->phone,
                    'name' => $validation->name ?? $user->name
                ]);

                $photoPath = $this->saveSingleFile(request(),"schools/images","logo");

                $user->school->update([
                    "title"=>$validation->schoolName ?? $user->school->title,
                    "logo" => $photoPath ?? $user->school->logo ?? null,
                ]);
                break;
            case config("constant.roles.student"):
            case config("constant.roles.parent"):
                return $this->error("permissionForUser",403);
                break;

        }
        return $this->successMessage();
    }

}
