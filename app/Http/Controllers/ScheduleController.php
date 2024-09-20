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
                    if ( is_int($subKey) ){
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
