<?php

namespace App\Http\Controllers;

use App\Http\Requests\School\SchoolValidation;
use App\Http\Resources\School\SchoolResource;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function store(Request $request,SchoolValidation $validation){
        return DB::transaction(function () use($request,$validation) {

            $user = auth()->user();
            if ($user->role != config("constant.roles.manager"))
                return $this->error("permissionForUser", 403);

            $gender = $validation->gender == 'male' ? 0 : ($validation->gender == 'female' ? 1 : null);

            $school = $user->school;
            $photoPath = $this->saveSingleFile($request,"schools/images","logo");

            if ($school != null) {
                $school->update([
                    "title" => $validation->title,
                    "gender" => $gender,
                    "phone" => $validation->phone,
                    "logo" => $photoPath,
                    "postalCode" => $validation->postalCode,
                    "bankAccount" => $validation->bankAccount,
                    "website" => $validation->website,
                    "socialMedia" => $validation->socialMedia,
                ]);
            } else {
                School::create([
                    "user_id" => $user->id,
                    "title" => $validation->title,
                    "gender" => $gender,
                    "phone" => $validation->phone,
                    "logo" => $photoPath,
                    "location" => $validation->location,
                    "postalCode" => $validation->postalCode,
                    "bankAccount" => $validation->bankAccount,
                    "website" => $validation->website,
                    "socialMedia" => $validation->socialMedia,
                ]);
            }


            return $this->successMessage();
        });
    }

    public function show(){
        return new SchoolResource(auth()->user()->school ?? null);
    }
}
