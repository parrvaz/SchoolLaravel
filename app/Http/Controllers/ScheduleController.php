<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bell\BellStoreValidation;
use App\Http\Requests\Bell\ScheduleStoreValidation;
use App\Http\Requests\Exam\ExamStoreValidation;
use App\Models\Absent;
use App\Models\Exam;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(Request $request,ScheduleStoreValidation $validation){

        $items = [];
        foreach ($validation->list as $item){
            $items[] = [
                'course_id' => $item['course_id'],
                'bell_id' => $item['bell_id'],
                'classroom_id' => $validation->classroom_id,
                'day' => $item['day'],

            ];
        }

        $schedule = Schedule::insert($items);

        return $this->successMessage();
    }

    public function update(ScheduleStoreValidation $validation, $userGrade, Schedule $schedule){
        return $schedule;
    }
}
