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
//        $allAbsents =  Absent::where("date",$validation->date)->get();
//        $allAbsents = $allAbsents->groupBy('classroom_id');
//
//        $data=[];
//        foreach ($allAbsents as $classroom_id => $absents) {
//            $studentsData = [];
//
//            foreach ($absents as $absent) {
//                $students = $absent->students;
//
//                foreach ($students as $student) {
//                    // بررسی اینکه آیا دانش‌آموز قبلاً در آرایه وجود دارد
//                    $existingStudentIndex = array_search($student->id, array_column($studentsData, 'student_id'));
//
//                    $bellInfo = [
//                        "bell_id" => $absent->bell_id,
//                        "order" => $absent->bell->order,
//                        "report" => $absent->user->name,
//                    ];
//
//                    // اگر دانش‌آموز قبلاً در آرایه وجود دارد
//                    if ($existingStudentIndex !== false) {
//                        // افزودن اطلاعات bells جدید به دانش‌آموز
//                        $studentsData[$existingStudentIndex]['bells'][] = $bellInfo;
//                    } else {
//                        // اگر دانش‌آموز جدید است، اضافه کردن آن به آرایه
//                        $studentsData[] = [
//                            "student_id" => $student->id,
//                            "student" => $student->name,
//                            "fatherPhone" => $student->fatherPhone,
//                            "bells" => [$bellInfo] // افزودن اطلاعات bells به‌صورت آرایه
//                        ];
//                    }
//                }
//            }
//
//            $data[] = [
//                "classroom_id" => $classroom_id,
//                "classroom" => Classroom::find($classroom_id)->title,
//                "students" => $studentsData
//            ];
//        }
//
//        return response()->json(['data' => $data]);

//        $allAbsents = Absent::where("date", $validation->date)->get();
//        $allAbsents = $allAbsents->groupBy('classroom_id');
//
//// دریافت تمام زنگ‌ها برای همه کلاس‌ها
//        $allBells = Bell::orderBy('order')->get();
//
//        $data = [];
//
//        foreach ($allAbsents as $classroom_id => $absents) {
//            $studentsData = [];
//            $students = [];
//
//            // دریافت لیست تمام دانش‌آموزان غایب از کلاس
//            foreach ($absents as $absent) {
//                foreach ($absent->students as $student) {
//                    // بررسی اینکه آیا دانش‌آموز قبلاً در آرایه وجود دارد
//                    if (!isset($students[$student->id])) {
//                        $students[$student->id] = [
//                            "student_id" => $student->id,
//                            "student" => $student->name,
//                            "fatherPhone" => $student->fatherPhone,
//                            "bells" => []
//                        ];
//                    }
//
//                    // افزودن وضعیت غیاب برای زنگ خاص
//                    $students[$student->id]['bells'][$absent->bell_id] = [
//                        "bell_id" => $absent->bell_id,
//                        "order" => $absent->bell->order,
//                        "status" => "absent",
//                        "report" => $absent->user->name
//                    ];
//                }
//            }
//
//            // اضافه کردن وضعیت هر زنگ به دانش‌آموزان
//            foreach ($students as $student_id => &$student) {
//                foreach ($allBells as $bell) {
//                    if (!isset($student['bells'][$bell->id])) {
//                        // اگر زنگی برای دانش‌آموز ثبت نشده بود، آن را اضافه کنید
//                        $attendanceRecorded = Absent::where('date', $validation->date)
//                            ->where('bell_id', $bell->id)
//                            ->where('classroom_id', $classroom_id)
//                            ->exists();
//
//                        $student['bells'][$bell->id] = [
//                            "bell_id" => $bell->id,
//                            "order" => $bell->order,
//                            "status" => $attendanceRecorded ? "present" : "notRegistered",
//                            "report" => null // اگر غایب نبود یا وضعیت ثبت نشده بود، گزارشی وجود ندارد
//                        ];
//                    }
//                }
//
//                // مرتب‌سازی زنگ‌ها بر اساس order
//                usort($student['bells'], function($a, $b) {
//                    return $a['order'] - $b['order'];
//                });
//            }
//
//            $data[] = [
//                "classroom_id" => $classroom_id,
//                "classroom" => Classroom::find($classroom_id)->title,
//                "students" => array_values($students)
//            ];
//        }
//
//        return response()->json(['data' => $data]);


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
