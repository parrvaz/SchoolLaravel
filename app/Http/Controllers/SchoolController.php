<?php

namespace App\Http\Controllers;

use App\Http\Requests\School\SchoolValidation;
use App\Http\Resources\School\SchoolResource;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function store(SchoolValidation $validation){
        $user = auth()->user();
        if ($user->role != config("constant.roles.manager"))
            return $this->error("permissionForUser",403);

        $school = $user->school;
        if ($school != null){
            $school->update([
                "title"=>$validation->title,
                "gender"=>$validation->gender,
                "phone"=>$validation->phone,
                "logo"=>$validation->logo,
                "postalCode"=>$validation->postalCode,
                "bankAccount"=>$validation->bankAccount,
                "website"=>$validation->website,
                "socialMedia"=>$validation->socialMedia,
            ]);
        }else{
            School::create([
                "user_id"=> $user->id,
                "title"=>$validation->title,
                "gender"=>$validation->gender,
                "phone"=>$validation->phone,
                "logo"=>$validation->logo,
                "location"=>$validation->location,
                "postalCode"=>$validation->postalCode,
                "bankAccount"=>$validation->bankAccount,
                "website"=>$validation->website,
                "socialMedia"=>$validation->socialMedia,
            ]);
        }


        return $this->successMessage();
    }

    public function show(){
        return new SchoolResource(auth()->user()->school ?? null);
    }
}
