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
    public function store(Request $request,ScheduleStoreValidation $validation){

        if (Classroom::find($validation->classroom_id)->schedules->count() > 0)
            return $this->error();

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

    public function update(ScheduleStoreValidation $validation, $userGrade, Classroom $classroom){
        return DB::transaction(function () use($classroom,$validation) {
            Schedule::where("classroom_id", $classroom->id)->delete();

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

        });
    }

    public function show(Request $request){
        $classroomIds = $request->userGrade->classrooms->pluck("id");
        return new ScheduleCollection(Schedule::whereIn("classroom_id",$classroomIds  )->get() );
    }


    public function showSingle( $userGrade,Classroom $classroom){
        return new ScheduleCollection( $classroom->schedules);
    }

    public function delete($userGrade,Classroom $classroom){
        $classroom->schedules()->delete();
        return $this->successMessage();
    }
}
