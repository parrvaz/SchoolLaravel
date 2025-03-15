<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bell\AbsentStoreValidation;
use App\Http\Requests\Bell\BellStoreValidation;
use App\Http\Requests\Bell\SetJustifiedValidation;
use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Bell\BellCollection;
use App\Models\Absent;
use App\Models\AbsentStudent;
use App\Models\Bell;
use App\Models\Classroom;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsentController extends Controller
{
    public function store(Request $request,AbsentStoreValidation $validation){
        return DB::transaction(function () use($request,$validation) {
            $date= self::jToG($validation->date);
            $user = auth()->user();
            $oldAbsent = Absent::where("date",$date)
                ->where("classroom_id",$validation->classroom_id)
                ->where("bell_id",$validation->bell_id)->first();
            //delete old
            if ($oldAbsent!=null){
                if ($user->role == config("constant.roles.assistant")
                    || $user->role == config("constant.roles.manager"))
                    $oldAbsent->delete();
                else if ($user->role == config("constant.roles.teacher") && $user->id == $oldAbsent->user_id)
                    $oldAbsent->delete();
                else
                    return $this->error("permissionForUser",403);

            }

            $absent = Absent::create([
                "user_id" => $user->id,
                "date" => $date ,
                "bell_id" => $validation->bell_id,
                "classroom_id" => $validation->classroom_id
            ]);
            $absent->students()->attach($validation->students);
            return $this->successMessage();
        });
    }

    public function update(AbsentStoreValidation $validation,$schoolGrade,Absent $absent){
        return DB::transaction(function () use($absent,$validation) {

            $absent->students()->detach();

            $absent->update([
                "date" => self::jToG($validation->date),
                "bell_id" => $validation->bell_id,
                "classroom_id" => $validation->classroom_id
            ]);
            $absent->students()->attach($validation->students);

            return $this->successMessage();
        });
    }

    public function setJustified(Request $request,SetJustifiedValidation $validation){
        $absent_students = AbsentStudent::where("student_id",$validation->student_id)
            ->whereHas('absent', function ($query)use($validation) {
                return $query->where('date', self::jToG($validation->date));
            })
        ->get();

        if ($absent_students->count() > 0){
            $justified = $absent_students->first()->isJustified;
            $ids = $absent_students->pluck("id")->toArray();

            AbsentStudent::whereIn("id",$ids)->update([
                "isJustified"=> ! $justified
            ]);

            return $this->successMessage();
        }
        else
            return $this->error("dontExist");

    }

    public function show(Request $request,FilterValidation $validation){
        $date = self::jToG($validation->date);

        $allAbsents = Absent::whereIn("classroom_id",$request->schoolGrade->classrooms->pluck("id"))
            ->where("date", $date)->get();
        $allAbsents = $allAbsents->groupBy('classroom_id');

        $allBells = Bell::where("school_id",$request->schoolGrade->school_id)->orderBy('order')->get();

        $data = [];

        foreach ($allAbsents as $classroom_id => $absents) {
            $studentsData = [];

            // دریافت لیست تمام دانش‌آموزان غایب از کلاس
            foreach ($absents as $absent) {
                foreach ($absent->absentStudents as &$student) {
                    $studentModel = $student->student;
                    // بررسی اینکه آیا دانش‌آموز قبلاً در آرایه وجود دارد
                    if (!isset($studentsData[$studentModel->id])) {
                        $studentsData[$studentModel->id] = [
                            "student_id" => $studentModel->id,
                            "student" => $studentModel->name,
                            "fatherPhone" => $studentModel->fatherPhone,
                            "bells" => []
                        ];
                    }

                    // استفاده از 'order' به عنوان کلید در 'bells'
                    $order = $absent->bell->order;

                    // افزودن وضعیت غیاب برای زنگ خاص
                    $studentsData[$studentModel->id]['bells'][$order] = [
                        "status" => $student->isJustified ? "justified" :"absent",
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
                        $attendanceRecorded = Absent::where('date', $date)
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

            if ($studentsData==null)
                continue;

            $data[] = [
                "classroom_id" => $classroom_id,
                "classroom" => Classroom::find($classroom_id)->title,
                "students" => array_values($studentsData),
                "count"=> count($studentsData)
            ];

        }

        return response()->json(['data' => $data]);
    }


    public function delete($schoolGrade,Absent $absent){
        return DB::transaction(function () use($absent) {

            $absent->students()->detach();
            $absent->delete();
            return $this->successMessage();
        });
    }
}
