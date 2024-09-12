<?php

namespace App\Http\Controllers;

use App\Http\Requests\Score\ScoreValidation;
use App\Http\Requests\Score\StudentTestValidation;
use App\Http\Requests\Score\TestValidation;
use App\Http\Resources\Score\StudentTestScoreCollection;
use App\Http\Resources\Score\TestCollection;
use App\Http\Resources\Score\TestResource;
use App\Models\StudentTestCourse;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function store(Request $request, TestValidation $validation){
        return DB::transaction(function () use($request,$validation) {
            $test = Test::create([
                'user_grade_id' => $request->userGrade->id,
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

    public function storeStudents($userGrade,Test $test,StudentTestValidation $validation){
        return DB::transaction(function () use($test,$validation) {
            //todo:check test course ids
            $data = [];
            foreach ($validation->students as $std) {
                foreach ($std['scores'] as $score) {
                    $row = [];
                    $row['test_course_id'] = $score['test_course_id'];
                    $row['student_id'] = $std['student_id'];
                    $row['score'] = $score['score'];
                    $row['balance'] = $score['balance'] ?? 0;

                    array_push($data, $row);
                }
            }

            $studentTestCourse = StudentTestCourse::insert($data);
            return new StudentTestScoreCollection($studentTestCourse);
        });
    }

    public function show(Request $request){
        return new TestCollection($request->userGrade->tests()->paginate(config('constant.bigPaginate')));
    }

    public function showSingle($userGrade,Test $test){
        return new TestResource($test);
    }



    public function update(ScoreValidation $validation,$userGrade, Test $test){
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

            return new TestResource($test);
        });
    }

    public function delete($userGrade,Test $test){
        return DB::transaction(function () use($test) {
            foreach ($test->courses() as $course){
                $this->deleteTestContents($test);
                $this->deleteTestStudents($test);
            }


            $this->deleteTestCourses($test);
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

    private function deleteTestCourses(Test $test)
    {
    }
}
