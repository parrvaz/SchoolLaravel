<?php

namespace App\Http\Controllers;

use App\Http\Requests\School\SchoolValidation;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function store(SchoolValidation $validation){
        $user = auth()->user();
        if ($user->role != config("constant.roles.manager"))
            return $this->error("permissionForUser",403);
        School::create([
            "user_id"=> $user->id,
            "title"=>$validation->title,
            "gender"=>$validation->gender,
            "phone"=>$validation->phone,
            "logo"=>$validation->logo,
            "location"=>$validation->location,
        ]);

        return $this->successMessage();
    }
}
