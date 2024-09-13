<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teacher\TeacherValidation;
use App\Http\Resources\Teacher\TeacherCollection;
use App\Http\Resources\Teacher\TeacherResource;
use App\Models\Assistant;
use App\Models\ModelHasRole;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssistantController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request,TeacherValidation $validation)
    {
        return DB::transaction(function () use($request,$validation) {

            $assistant = Assistant::create([
                'firstName'=>$validation->firstName,
                'lastName'=>$validation->lastName,
                'nationalId'=>$validation->nationalId,
                'degree'=>$validation->degree,
                'personalId'=>$validation->personalId,
                'user_grade_id'=>$request->userGrade->id,
                'phone'=>$validation->phone,

            ]);
            //create user
            $user = User::create([
                "name"=> $assistant->firstName." ".$assistant->lastName,
                "phone"=>$assistant->phone,
                "password"=> bcrypt($assistant->nationalId),
            ]);

            //assign role
            $user->assignRole('assistant');
            $user->modelHasRole()->update(["idInRole"=>$assistant->id ]);

            return new TeacherResource($assistant);
        });

    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new TeacherCollection($request->userGrade->assistants()->orderBy("lastName")->paginate($request->perPage??config("constant.bigPaginate")));
    }

    public function showSingle($userGrade,Assistant $assistant)
    {
        return new TeacherResource($assistant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherValidation $validation,$userGrade, Assistant $assistant)
    {
        $assistant->update([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'degree'=>$validation->degree,
            'personalId'=>$validation->personalId,
            'phone'=>$validation->phone,
        ]);

        return new TeacherResource($assistant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($userGrade,Assistant $assistant)
    {
        User::where("phone",$assistant->phone)->delete();
        ModelHasRole::where("idInRole",$assistant->id)->delete();

        $assistant->delete();
        return $this->successMessage();
    }
}
