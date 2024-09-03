<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StudentValidation;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request,StudentValidation $validation)
    {
        $student = Student::create([
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
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new StudentCollection( Student::whereHas('classroom', function($query) use($request) {
            return $query->where('user_grade_id', $request['userGrade']->id);
        })->orderBy("classroom_id")->orderBy("lastName")->paginate($request->perPage?? config('constant.bigPaginate')));
    }

    public function showSingle(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentValidation $validation, Student $student)
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
    public function delete(Student $student)
    {
            $student->delete();
            return $this->successMessage();
    }
}
