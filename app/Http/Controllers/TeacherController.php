<?php

namespace App\Http\Controllers;

use App\Events\UserCreate;
use App\Http\Requests\Student\StudentValidation;
use App\Http\Requests\Teacher\TeacherAddValidation;
use App\Http\Requests\Teacher\TeacherUpdateValidation;
use App\Http\Requests\Teacher\TeacherValidation;
use App\Http\Resources\Grade\SchoolGradeCollection;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\Teacher\TeacherCollection;
use App\Http\Resources\Teacher\TeacherResource;
use App\Models\ModelHasRole;
use App\Models\Role;
use App\Models\SchoolGrade;
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
            $school_id=$request->schoolGrade->school_id;

            //check if teacher exist before
            $tch = Teacher::where("nationalId",$validation->nationalId)->first();
            if ($tch!=null){
                $school =  $tch->school->where("id", $school_id)->first();//check teacher already in list
                if ($school== null)
                    return $this->add($request, $tch);
                else
                    return $this->error('wasAdd');
            }
            $usr = User::where("phone",$validation->phone)->first();
            if ($usr!=null){
                return $this->error('phoneTaken');
            }



        $teacher = Teacher::create([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'degree'=>$validation->degree,
            'personalId'=>$validation->personalId,
            'school_id'=>$school_id,
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

        UserCreate::dispatch($user);


            return new TeacherResource($teacher);

        });

    }

    public function setAssistant(Request $request,$schoolGrade,Teacher $teacher){
        $teacher->update([
            "isAssistant"=> !$teacher->isAssistant
        ]);

        return $this->successMessage();
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new TeacherCollection($request->schoolGrade->teachers()->orderBy("lastName")->get());
    }

    public function showSingle(Request $request, $schoolGrade,Teacher $teacher)
    {
        if ( in_array( $request->schoolGrade->school_id, $teacher->school->pluck("id")->toArray()) )
            return new TeacherResource($teacher);
        else
            return $this->error("permissionForUser",403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,TeacherUpdateValidation $validation,$schoolGrade, Teacher $teacher)
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

    public function showSchoolGradeOfTeacher(){
        $teacher = auth()->user()->teacher;
        $schoolGradeIds =  $teacher->classrooms->pluck("school_grade_id")->unique()->values();

        return new SchoolGradeCollection(SchoolGrade::whereIn("id",$schoolGradeIds)->get());
    }


    private function add(Request $request, $teacher){
        //add teacher to school
        $request->schoolGrade->school->teachers()->attach($teacher);
        (new SMSController())->UserAddInList($teacher->user);
        return $this->warningMessage();

    }


}
