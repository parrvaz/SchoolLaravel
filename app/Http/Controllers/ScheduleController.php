<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bell\BellStoreValidation;
use App\Http\Requests\Bell\ScheduleStoreValidation;
use App\Http\Requests\Exam\ExamStoreValidation;
use App\Http\Resources\Bell\ScheduleCollection;
use App\Http\Resources\Bell\ScheduleResource;
use App\Models\Absent;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function store(ScheduleStoreValidation $validation,$userGrade,Classroom $classroom){
        if ($classroom == null)
            return $this->error();

        return DB::transaction(function () use($classroom,$validation) {

            $classroom->schedules()->delete();

            $bells = $classroom->userGrade->user->bells->pluck("id","order");

            $items = [];
            foreach ($validation->schedule as $key=> $item) {
                $bell_id= $bells[$key];
                foreach ($item as  $subKey=> $subItem){
                    if ( is_int($subKey) && $subItem!=null){
                        $day = $subKey;
                        $course_id = $subItem;
                        $items[] = [
                            'course_id' => $course_id,
                            'bell_id' => $bell_id,
                            'classroom_id' => $classroom->id,
                            'day' => $day,
                        ];
                    }

                }
            }

            $schedule = Schedule::insert($items);

            return $this->successMessage();
        });
    }




    public function show(Request $request){
        $classrooms = $request->userGrade->classrooms;

        $data = [];
        foreach ($classrooms as $classroom){
            $data[]=[
                'classroom_id' => $classroom->id,
                'classroom' => $classroom->title,
                'schedule' => $this->createSchedule($classroom->schedules),
            ];
        }
        return response()->json(['data' => $data]);
    }


    public function showSingle( $userGrade,Classroom $classroom){
        $schedules = $classroom->schedules;
        $formattedSchedule = $this->createSchedule($schedules);
        // بازگرداندن نتیجه به فرمت مورد نظر
        return response()->json(['schedule' => $formattedSchedule]);
    }

    public function delete($userGrade,Classroom $classroom){
        $classroom->schedules()->delete();
        return $this->successMessage();
    }
    private function createSchedule ($schedules){
        // ایجاد آرایه برای ذخیره‌ی داده‌های جدید
        $formattedSchedule = [];

        // نگاشت عدد روز به نام روز هفته
        $daysOfWeek = [
            1 => 'sat', // شنبه
            2 => 'sun', // یکشنبه
            3 => 'mon', // دوشنبه
            4 => 'tue', // سه‌شنبه
            5 => 'wed', // چهارشنبه
            6 => 'thu', // پنج‌شنبه
            7 => 'fri'  // جمعه (اختیاری)
        ];

        // حلقه برای پردازش هر آیتم
        foreach ($schedules as $schedule) {
            $order = $schedule->bell->order;

            $day = $schedule->day; // شناسه روز
            $course_id = $schedule->course_id; // شناسه درس
            $course_name = $schedule->course->name; // نام درس

            // اگر bell_id قبلاً در آرایه نیست، آن را ایجاد می‌کنیم
            if (!isset($formattedSchedule[$order])) {
                // ایجاد آرایه جدید برای زنگ
                $formattedSchedule[$order] = [
                    'sat' => '',
                    'sun' => '',
                    'mon' => '',
                    'tue' => '',
                    'wed' => '',
                    'thu' => '',
                    'fri' => ''
                ];
            }

            // ذخیره‌سازی course_id برای هر bell_id و day
            $formattedSchedule[$order][$day] = $course_id;

            // ذخیره‌سازی نام درس برای هر روز هفته (برای نمایش در فرانت)
            $formattedSchedule[$order][$daysOfWeek[$day]] = $course_name;
        }

        return $formattedSchedule;
    }
}
