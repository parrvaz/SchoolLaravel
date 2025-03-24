<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateValidation;
use App\Models\SchoolGrade;


class UserController extends Controller
{
    public function update(UserUpdateValidation $validation){
        $user =  auth()->user();
        $role =$user->role;
        switch ($role) {
            case config("constant.roles.assistant"):
            case config("constant.roles.teacher"):
                $teacher = auth()->user()->teacher;
                //change phone if is changed
                if ($validation->phone != $teacher->phone && $validation->phone !=null) {
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
                break;
            case config("constant.roles.student"):
            case config("constant.roles.parent"):
                return $this->error("permissionForUser",403);
                break;

        }
        return $this->successMessage();
    }
}
