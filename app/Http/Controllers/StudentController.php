<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StudentValidation;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
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

        return new StudentResource($student);
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
    public function update(StudentValidation $validation,$userGrade, Student $student)
    {
        $student->update([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'classroom_id'=>$validation->classroom_id,
            'birthday'=>$validation->birthday,
            'onlyChild'=>$validation->onlyChild,
            'address'=>$validation->address,
            'phone'=>$validation->phone,
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
            $student->delete();
            return $this->successMessage();
    }
}
