<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StudentValidation;
use App\Http\Requests\Teacher\TeacherAddValidation;
use App\Http\Requests\Teacher\TeacherUpdateValidation;
use App\Http\Requests\Teacher\TeacherValidation;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\Teacher\TeacherCollection;
use App\Http\Resources\Teacher\TeacherResource;
use App\Models\ModelHasRole;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Database\Factories\TeacherFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request,TeacherValidation $validation)
    {
        return DB::transaction(function () use($request,$validation) {

        $teacher = Teacher::create([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'degree'=>$validation->degree,
            'personalId'=>$validation->personalId,
            'school_id'=>$request->schoolGrade->school_id,
            'phone'=>$validation->phone,
            'isAssistant'=>$validation->isAssistant,
        ]);


        //create user
        $user = User::create([
            "name"=> $teacher->firstName." ".$teacher->lastName,
            "phone"=>$teacher->phone,
            "password"=> bcrypt($teacher->nationalId),
        ]);

        //assign role
        if (!$validation->isAssistant){
            $user->assignRole('teacher');
        }else{
            $user->assignRole('assistant');
        }
        $user->modelHasRole()->update(["idInRole"=>$teacher->id ]);

        //add teacher to school
        $request->schoolGrade->school->teachers()->attach($teacher);

        return new TeacherResource($teacher);

        });

    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new TeacherCollection($request->schoolGrade->teachers()->orderBy("lastName")->get());
    }

    public function showSingle($schoolGrade,Teacher $teacher)
    {
        return new TeacherResource($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherUpdateValidation $validation,$schoolGrade, Teacher $teacher)
    {
        return DB::transaction(function () use($teacher,$validation) {

            //change role if is changed
            if ($validation->isAssistant != $teacher->isAssistant) {
                $userId= $teacher->user->id;
                if (!$validation->isAssistant)
                    ModelHasRole::where("idInRole",$teacher->id)->where("model_id",$userId)->update([
                        'role_id' => config("constant.roles.teacher"),

                    ]);
                else
                    ModelHasRole::where("idInRole",$teacher->id)->where("model_id",$userId)->update([
                        'role_id' => config("constant.roles.assistant"),
                    ]);
            }


            //change phone if is changed
            if ($validation->phone != $teacher->phone) {
                $teacher->user->update([
                    'phone'=>$validation->phone
                ]);
            }


            //change password if is changed
            if ($validation->nationalId != $teacher->nationalId) {
                $teacher->user->update([
                    'password' => bcrypt($validation->nationalId)
                ]);
            }


            //update other values
            $teacher->update([
                'firstName' => $validation->firstName,
                'lastName' => $validation->lastName,
                'nationalId' => $validation->nationalId,
                'degree' => $validation->degree,
                'personalId' => $validation->personalId,
                'isAssistant' => $validation->isAssistant,
                'phone' => $validation->phone,
            ]);


            return new TeacherResource($teacher);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request,$schoolGrade,Teacher $teacher)
    {
        return DB::transaction(function () use($teacher,$request) {
            if ($teacher->user->absents->count() > 0 ){
                return $this->error("hasAbsent");
            }else{
                $teacher->classCoursesSchool($request->schoolGrade)->delete();
                $request->schoolGrade->school->teachers()->detach($teacher);
            }

//            User::where("phone", $teacher->phone)->delete();
//            ModelHasRole::where("idInRole", $teacher->id)->delete();
//            $teacher->delete();
            return $this->successMessage();
        });
    }

    public function add(Request $request,TeacherAddValidation $validation){
        $teacher = Teacher::where("nationalId",$validation->nationalId)->first();
        //add teacher to school
       $request->schoolGrade->school->teachers()->attach($teacher);
       return $this->successMessage();
    }
}
