<?php

namespace App\Http\Controllers;

use App\Http\Requests\Score\ScoreValidation;
use App\Http\Requests\Score\TestValidation;
use App\Http\Resources\Score\TestResource;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function store(Request $request, TestValidation $validation){
        return DB::transaction(function () use($request,$validation) {
            $test = Test::create([
                'user_grade_id' => $request['userGrade']->id,
                'classroom_id' => $validation->classroom_id,
                'title' => $validation->title,
                'date' => $validation->date,
            ]);

            //create courses item
            $courses=$validation->courses;
            $contents=[];
            $data=[];
            for ($i=0; $i<count( $courses) ;$i++){
               $data[$i]=[
                   'course_id'=> $courses[$i]['course_id'],
                   'expected'=>$courses[$i]['expected'],
                   'average'=> $courses[$i]['average']
               ];
                $contents[$courses[$i]['course_id']]= $courses[$i]['contents'];
            }
            $coursesCreate = $test->courses()->createMany($data);

            //create contents item
            foreach ($coursesCreate as $cc){
                $cc->contents()->attach($contents[$cc->course_id] );
            }

            return new TestResource($test);
        });
    }

    public function show(Request $request){
        return new ScoreCollection($request['userGrade']->tests()->paginate(config('constant.bigPaginate')));
    }

    public function showSingle(Test $test){
        return new ScoreResource($test);
    }

    public function update(ScoreValidation $validation, Test $test){
        return DB::transaction(function () use($test,$validation) {

            //delete old items
            $this->deleteTestContents($test);
            $this->deleteTestStudents($test);

            //update test main data
            $test->update([
                'classroom_id' => $validation->classroom_id,
                'date' => $validation->date,
                'course_id' => $validation->course_id,
                'expected' => $validation->expected,
                'totalScore' => $validation->totalScore,
            ]);

            //create content items
            $test->contents()->attach($validation->contents);

            //create student items
            $test->students()->createMany($validation->students);

            return new ScoreResource($test);
        });
    }

    public function delete(Test $test){
        return DB::transaction(function () use($test) {

            $this->deleteTestContents($test);
            $this->deleteTestStudents($test);
            $test->delete();
            return $this->successMessage();
        });
    }

    private function deleteTestContents(Test $test)
    {
        $test->contents()->detach();
    }

    private function deleteTestStudents(Test $test)
    {
        $test->students()->delete();
    }
}
