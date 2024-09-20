<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bell\AbsentStoreValidation;
use App\Http\Requests\Bell\BellStoreValidation;
use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Bell\AbsentCollection;
use App\Http\Resources\Bell\AbsentResource;
use App\Http\Resources\Bell\BellCollection;
use App\Models\Absent;
use App\Models\Bell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsentController extends Controller
{
    public function store(Request $request,AbsentStoreValidation $validation){

        return DB::transaction(function () use($request,$validation) {

            $absent = Absent::create([
                "user_id" => auth()->user()->id,
                "date" => $validation->date,
                "bell_id" => $validation->bell_id,
                "classroom_id" => $validation->classroom_id
            ]);

            $absent->students()->attach($validation->students);

            return new AbsentResource($absent);
        });
    }

    public function update(AbsentStoreValidation $validation,$userGrade,Absent $absent){
        return DB::transaction(function () use($absent,$validation) {

            $absent->students()->detach();

            $absent->update([
                "date" => $validation->date,
                "bell_id" => $validation->bell_id,
                "classroom_id" => $validation->classroom_id
            ]);
            $absent->students()->attach($validation->students);

            return new AbsentResource($absent);
        });
    }

    public function show(Request $request,FilterValidation $validation){
        return new AbsentCollection($request->userGrade->absents);
    }

    public function delete($userGrade,Absent $absent){
        return DB::transaction(function () use($absent) {

            $absent->students()->detach();
            $absent->delete();
            return $this->successMessage();
        });
    }
}
