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

            return $this->successMessage();
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
        $date = $validation->date;

        $allAbsents = Absent::whereIn("classroom_id",$request->userGrade->classrooms->pluck("id"))->where("date", $validation->date)->get();
        $allAbsents = $allAbsents->groupBy('classroom_id');

        $allBells = Bell::orderBy('order')->get();

        $data = [];

        foreach ($allAbsents as $classroom_id => $absents) {
            $studentsData = [];
//            $students = [];



            // دریافت لیست تمام دانش‌آموزان غایب از کلاس
            foreach ($absents as $absent) {
                foreach ($absent->students as &$student) {

                    // بررسی اینکه آیا دانش‌آموز قبلاً در آرایه وجود دارد
                    if (!isset($studentsData[$student->id])) {
                        $studentsData[$student->id] = [
                            "student_id" => $student->id,
                            "student" => $student->name,
                            "fatherPhone" => $student->fatherPhone,
                            "bells" => []
                        ];
                    }

                    // استفاده از 'order' به عنوان کلید در 'bells'
                    $order = $absent->bell->order;

                    // افزودن وضعیت غیاب برای زنگ خاص
                    $studentsData[$student->id]['bells'][$order] = [
                        "status" => "absent",
                        "reporter" => $absent->user->name
                    ];
                }

            }


            // اضافه کردن وضعیت هر زنگ به دانش‌آموزان
            foreach ($studentsData as $student_id => &$student) {
                foreach ($allBells as $bell) {
                    $order = $bell->order;

                    if (!isset($student['bells'][$order])) {
                        // اگر زنگی برای دانش‌آموز ثبت نشده بود، آن را اضافه کنید
                        $attendanceRecorded = Absent::where('date', $validation->date)
                            ->where('bell_id', $bell->id)
                            ->where('classroom_id', $classroom_id)
                            ->exists();

                        $student['bells'][$order] = [
                            "status" => $attendanceRecorded ? "present" : "notRegistered",
                            "reporter" => null
                        ];
                    }
                }

                // مرتب‌سازی کلیدهای 'bells' بر اساس 'order'
                ksort($student['bells']);
            }


            $data[] = [
                "classroom_id" => $classroom_id,
                "classroom" => Classroom::find($classroom_id)->title,
                "students" => array_values($studentsData)
            ];

        }

        return response()->json(['data' => $data]);
    }

    public function teachersMiss(Request $request,FilterValidation $validation){
        $bells = $request->userGrade->user->bells->pluck("id");

        return Absent::where("date",$validation->date)->whereIn("bell_id",$bells)->get();
    }
    public function delete($userGrade,Absent $absent){
        return DB::transaction(function () use($absent) {

            $absent->students()->detach();
            $absent->delete();
            return $this->successMessage();
        });
    }
}
