<?php

namespace App\Http\Controllers;

use App\Events\RoleDelete;
use App\Events\UserCreate;
use App\Http\Requests\Student\StudentUpdateValidation;
use App\Http\Requests\Student\StudentValidation;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use App\Imports\StudentsCreateImport;
use App\Imports\StudentsImport;
use App\Models\ModelHasRole;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request,StudentValidation $validation)
    {
        if ($validation->phone == $validation->fatherPhone)
            return $this->error("fatherPhone");
        $fatherAccount = User::where("phone",$validation->fatherPhone)->first();
        if ( $fatherAccount!= null &&  $fatherAccount->role != config("constant.roles.parent") )
            return $this->error("fatherPhoneTaken");

        return DB::transaction(function () use($request,$validation,$fatherAccount) {

            $photoPath = $this->saveSingleFile($request,"students/images");

            $student = Student::create([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'classroom_id'=>$validation->classroom_id,
            'birthday'=>self::jToG($validation->birthday) ?? null ,
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

        UserCreate::dispatch($user);

        if ($fatherAccount == null){
            //create parent user
            $user = User::create([
                "name"=> "ولی ". $student->firstName." ".$student->lastName,
                "phone"=>$student->fatherPhone,
                "password"=> bcrypt($student->nationalId),
            ]);
            //assign role
            $user->assignRole('parent');
            $user->modelHasRole()->update(["idInRole"=>$student->id ]);
            UserCreate::dispatch($user);
        }


            return $this->successMessage();

        });

    }


    public function import(Request $request)
    {
        return DB::transaction(function () use($request) {
            $import = new StudentsImport($request);
            Excel::import($import, $request->file('file'));
            $errors = $import->getErrors();
            if (!empty($errors)) {
                return response()->json([
                    'mistakes' => $errors,
                ], 422);
            }


                $file = $request->file('file');
                Excel::import(new StudentsCreateImport($request), $file->store('temp'));
                return $this->successMessage();

        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new StudentCollection( Student::whereHas('classroom', function($query) use($request) {
            return $query->where('school_grade_id', $request->schoolGrade->id);
        })->orderBy("classroom_id")->orderBy("lastName")->get());
    }

    public function showSingle( $schoolGrade,Student $student)
    {

        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,StudentUpdateValidation $validation,$schoolGrade, Student $student)
    {
        return DB::transaction(function () use($validation,$student,$request) {

            //change phone if is changed
            if ($validation->phone != $student->phone) {
                RoleDelete::dispatch($student->phone,$request->schoolGrade->school->title);
                $student->user->update([
                    'phone' => $validation->phone
                ]);
                UserCreate::dispatch($student->user);
            }

            //change father phone if is changed
            if ($validation->fatherPhone != $student->fatherPhone) {
                RoleDelete::dispatch($student->fatherPhone,$request->schoolGrade->school->title);
                $student->parentUser->update([
                    'phone' => $validation->fatherPhone
                ]);
                UserCreate::dispatch($student->parentUser);

            }

//            //change password if is changed
//            if (!$student->user->hasChanged &&  $validation->nationalId != $student->nationalId) {
//                $student->user->update([
//                    'password' => bcrypt($validation->nationalId)
//                ]);
//                $student->parentUser->update([
//                    'password' => bcrypt($validation->nationalId)
//                ]);
//            }

            if ($validation->classroom_id != $student->classroom_id) {
                $student->plan()->detach();
            }


            $student->update([
                'firstName' => $validation->firstName,
                'lastName' => $validation->lastName,
                'nationalId' => $validation->nationalId,
                'classroom_id' => $validation->classroom_id,
                'birthday' => self::jToG($validation->birthday) ?? null,
                'onlyChild' => $validation->onlyChild,
                'address' => $validation->address,
                'phone' => $validation->phone,
                'fatherPhone' => $validation->fatherPhone,
                'motherPhone' => $validation->motherPhone,
                'socialMediaID' => $validation->socialMediaID,
                'numberOfGlasses' => $validation->numberOfGlasses,
                'leftHand' => $validation->leftHand,
                'religion' => $validation->religion,
                'specialDisease' => $validation->specialDisease,
            ]);

            return new StudentResource($student);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request, $schoolGrade,Student $student)
    {
        return DB::transaction(function () use($student,$request) {

            User::where("phone", $student->phone)->delete();
            User::where("phone", $student->fatherPhone)->delete();
            ModelHasRole::where("idInRole", $student->id)->delete();
//            RoleDelete::dispatch($student->phone,$request->schoolGrade->school->title);
            RoleDelete::dispatch($student->fatherPhone,$request->schoolGrade->school->title);

            $student->delete();

            return $this->successMessage();
        });
    }

    public function sampleExcel(){
        $filePath = 'public/sample.xlsx'; // مسیر فایل در storage/app/public

        // ارسال فایل برای دانلود
        return Storage::download($filePath, 'sample.xlsx');
    }
}
