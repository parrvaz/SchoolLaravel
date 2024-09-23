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
        $allAbsents = Absent::where("date", $validation->date)->get();
        $allAbsents = $allAbsents->groupBy('classroom_id');

        $allBells = Bell::orderBy('order')->get();

        $data = [];

        foreach ($allAbsents as $classroom_id => $absents) {
            $studentsData = [];
            $students = [];

            // دریافت لیست تمام دانش‌آموزان غایب از کلاس
            foreach ($absents as $absent) {
                foreach ($absent->students as $student) {
                    // اطمینان حاصل کنیم که دانش‌آموز به کلاس درست تعلق دارد
                    if ($student->classroom_id != $classroom_id) {
                        continue; // اگر دانش‌آموز به این کلاس تعلق ندارد، آن را رد می‌کنیم
                    }

                    // بررسی اینکه آیا دانش‌آموز قبلاً در آرایه وجود دارد
                    if (!isset($students[$student->id])) {
                        $students[$student->id] = [
                            "student_id" => $student->id,
                            "student" => $student->name,
                            "fatherPhone" => $student->fatherPhone,
                            "bells" => []
                        ];
                    }

                    // افزودن وضعیت غیاب برای زنگ خاص
                    $students[$student->id]['bells'][$absent->bell->order] = [
                        "status" => "absent",
                        "report" => $absent->user->name
                    ];
                }
            }

            // اضافه کردن وضعیت هر زنگ به دانش‌آموزان
            foreach ($students as $student_id => &$student) {
                foreach ($allBells as $bell) {
                    if (!isset($student['bells'][$bell->order])) {
                        // اگر زنگی برای دانش‌آموز ثبت نشده بود، آن را اضافه کنید
                        $attendanceRecorded = Absent::where('date', $validation->date)
                            ->where('bell_id', $bell->id)
                            ->where('classroom_id', $classroom_id)
                            ->exists();

                        $student['bells'][$bell->order] = [
                            "status" => $attendanceRecorded ? "present" : "notRegistered",
                            "reporter" => null // اگر غایب نبود یا وضعیت ثبت نشده بود، گزارشی وجود ندارد
                        ];
                    }
                }
            }

            $data[] = [
                "classroom_id" => $classroom_id,
                "classroom" => Classroom::find($classroom_id)->title,
                "students" => array_values($students)
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
