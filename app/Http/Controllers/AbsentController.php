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
use App\Models\Classroom;
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

        $allAbsents =  Absent::where("date",$validation->date)->get();
        $allAbsents = $allAbsents->groupBy('classroom_id');


        $data=[];
        foreach ($allAbsents as $classroom_id => $absents) {
            $studentsData = [];

            foreach ($absents as $absent) {
                $students = $absent->students;

                foreach ($students as $student) {
                    // بررسی اینکه آیا دانش‌آموز قبلاً در آرایه وجود دارد
                    $existingStudentIndex = array_search($student->id, array_column($studentsData, 'student_id'));

                    $bellInfo = [
                        "bell_id" => $absent->bell_id,
                        "order" => $absent->bell->order,
                        "report" => $absent->user->name,
                    ];

                    // اگر دانش‌آموز قبلاً در آرایه وجود دارد
                    if ($existingStudentIndex !== false) {
                        // افزودن اطلاعات bells جدید به دانش‌آموز
                        $studentsData[$existingStudentIndex]['bells'][] = $bellInfo;
                    } else {
                        // اگر دانش‌آموز جدید است، اضافه کردن آن به آرایه
                        $studentsData[] = [
                            "student_id" => $student->id,
                            "student" => $student->name,
                            "fatherPhone" => $student->fatherPhone,
                            "bells" => [$bellInfo] // افزودن اطلاعات bells به‌صورت آرایه
                        ];
                    }
                }
            }

            $data[] = [
                "classroom_id" => $classroom_id,
                "classroom" => Classroom::find($classroom_id)->title,
                "students" => $studentsData
            ];
        }

        return response()->json(['absents' => $data]);
    }

    public function delete($userGrade,Absent $absent){
        return DB::transaction(function () use($absent) {

            $absent->students()->detach();
            $absent->delete();
            return $this->successMessage();
        });
    }
}
