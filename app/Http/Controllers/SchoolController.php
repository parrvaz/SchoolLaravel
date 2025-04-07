<?php

namespace App\Http\Controllers;

use App\Http\Requests\School\SchoolStoreValidation;
use App\Http\Requests\School\SchoolValidation;
use App\Http\Resources\School\SchoolResource;
use App\Models\School;
use App\Models\SchoolGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function testt(){
        $user = User::find(426);
      return  $students = $user->students;
    }


    public function update(Request $request,SchoolValidation $validation){
        return DB::transaction(function () use($request,$validation) {

            $user = auth()->user();
            if ($user->role != config("constant.roles.manager"))
                return $this->error("permissionForUser", 403);

            $gender = $validation->gender == 'male' ? 0 : ($validation->gender == 'female' ? 1 : null);

            $school = $user->school;
            $photoPath = $this->saveSingleFile($request,"schools/images","logo");


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


            return $this->successMessage();
        });
    }

    public function show(){
        return new SchoolResource(auth()->user()->school ?? null);
    }


    public function store(Request $request,SchoolStoreValidation $validation){
        return DB::transaction(function () use($request,$validation) {

            $user = auth()->user();
            $school = $user->school;
            if ($user->role != config("constant.roles.manager"))
                return $this->error("permissionForUser", 403);
            $gender = $validation->gender == 'male' ? 0 : ($validation->gender == 'female' ? 1 : null);
            $photoPath = $this->saveSingleFile($request,"schools/images","logo");
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

            $grades =[];
            foreach ($validation->grades as $grade){
                $grades[]=[
                    "school_id"=>$school->id,
                    "grade_id"=>$grade['grade_id'],
                    "title"=>$grade['title'],
                    'code' => Str::random(30),
                ];
            }
            $school->grades()->createMany($grades);

            return new SchoolResource($school);
        });
    }

    public static function decreaseFromWallet( $request,$price){
        $school =  $request->schoolGrade->school;
        $school->update(["wallet"=>$school->wallet - $price]);
    }
}
