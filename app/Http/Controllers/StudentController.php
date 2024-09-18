<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StudentUpdateValidation;
use App\Http\Requests\Student\StudentValidation;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use App\Models\ModelHasRole;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Models\UserGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request,StudentValidation $validation)
    {
        return DB::transaction(function () use($request,$validation) {

            // ذخیره تصویر در صورت آپلود
            $photoPath = null;
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $timestamp = now()->timestamp; // دریافت timestamp
                $extension = $file->getClientOriginalExtension(); // گرفتن پسوند فایل
                $filename = $timestamp . '.' . $extension; // ایجاد نام یونیک با timestamp

                $photoPath = $file->storeAs('images/students',$filename, 'public');
            }

            $student = Student::create([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'classroom_id'=>$validation->classroom_id,
            'birthday'=>$validation->birthday,
            'onlyChild'=>$validation->isOnlyChild,
            'address'=>$validation->address,
            'phone'=>$validation->phone,
            'fatherPhone'=>$validation->fatherPhone,
            'motherPhone'=>$validation->motherPhone,
            'socialMediaID'=>$validation->socialMediaID,
            'numberOfGlasses'=>$validation->numberOfGlasses,
            'leftHand'=>$validation->isLeftHand,
            'religion'=>$validation->religion,
            'specialDisease'=>$validation->specialDisease,
            'picture'=>$photoPath
        ]);


        //create user
        $user = User::create([
            "name"=> $student->firstName." ".$student->lastName,
            "phone"=>$student->phone,
            "password"=> bcrypt($student->nationalId),
        ]);
        //assign role
        $user->assignRole('student');
        $user->modelHasRole()->update(["idInRole"=>$student->id ]);


        //create parent user
            $user = User::create([
                "name"=> "ولی ". $student->firstName." ".$student->lastName,
                "phone"=>$student->fatherPhone,
                "password"=> bcrypt($student->nationalId),
            ]);
            //assign role
            $user->assignRole('parent');
            $user->modelHasRole()->update(["idInRole"=>$student->id ]);



            return $this->successMessage();

        });

    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new StudentCollection( Student::whereHas('classroom', function($query) use($request) {
            return $query->where('user_grade_id', $request->userGrade->id);
        })->orderBy("classroom_id")->orderBy("lastName")->paginate($request->perPage?? config('constant.bigPaginate')));
    }

    public function showSingle( $userGrade,Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentUpdateValidation $validation,$userGrade, Student $student)
    {

        //change phone if is changed
        if ($validation->phone != $student->phone) {
            $student->user->update([
                'phone'=>$validation->phone
            ]);
        }

        //change father phone if is changed
        if ($validation->fatherPhone != $student->fatherPhone) {
            $student->user->update([
                'fatherPhone'=>$validation->fatherPhone
            ]);
        }


        $student->update([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'classroom_id'=>$validation->classroom_id,
            'birthday'=>$validation->birthday,
            'onlyChild'=>$validation->onlyChild,
            'address'=>$validation->address,
            'phone'=>$validation->phone,
            'fatherPhone'=>$validation->fatherPhone,
            'motherPhone'=>$validation->motherPhone,
            'socialMediaID'=>$validation->socialMediaID,
            'numberOfGlasses'=>$validation->numberOfGlasses,
            'leftHand'=>$validation->leftHand,
            'religion'=>$validation->religion,
            'specialDisease'=>$validation->specialDisease,
        ]);

        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($userGrade,Student $student)
    {
        User::where("phone",$student->phone)->delete();
        User::where("phone",$student->fatherPhone)->delete();
        ModelHasRole::where("idInRole",$student->id)->delete();
       $student->delete();
       return $this->successMessage();
    }
}
