<?php

namespace App\Http\Controllers;

use App\Http\Requests\Score\ScoreValidation;
use App\Http\Resources\Score\ScoreCollection;
use App\Http\Resources\Score\ScoreResource;
use App\Models\ClassScore;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassScoreController extends Controller
{
    public function store(Request $request, ScoreValidation $validation){

        return DB::transaction(function () use($request,$validation) {
            $classScore = ClassScore::create([
                'user_grade_id' => $request->userGrade->id,
                'classroom_id' => $validation->classroom_id,
                'date' => $validation->date,
                'course_id' => $validation->course_id,
                'expected' => $validation->expected,
                'totalScore' => $validation->totalScore,
                'status' => $validation->status ?? false,
            ]);

            $classScore->contents()->attach($validation->contents);

            $classScore->students()->createMany($validation->students);

            return $this->successMessage();
        });
    }

    public function show(Request $request){
        return new ScoreCollection($request->userGrade->classScores()->paginate(config('constant.bigPaginate')));
    }

    public function showSingle(ClassScore $classScore){
        return new ScoreResource($classScore);
    }

    public function update(ScoreValidation $validation, ClassScore $classScore){
        return DB::transaction(function () use($classScore,$validation) {

            //delete old items
            $this->deleteClassScoreContents($classScore);
            $this->deleteClassScoreStudents($classScore);

            //update classScore main data
            $classScore->update([
                'classroom_id' => $validation->classroom_id,
                'date' => $validation->date,
                'course_id' => $validation->course_id,
                'expected' => $validation->expected,
                'totalScore' => $validation->totalScore,
            ]);

            //create content items
            $classScore->contents()->attach($validation->contents);

            //create student items
            $classScore->students()->createMany($validation->students);

            return new ScoreResource($classScore);
        });
    }

    public function delete(ClassScore $classScore){
        return DB::transaction(function () use($classScore) {

            $this->deleteClassScoreContents($classScore);
            $this->deleteClassScoreStudents($classScore);
            $classScore->delete();
            return $this->successMessage();
        });
    }

    private function deleteClassScoreContents(ClassScore $classScore)
    {
        $classScore->contents()->detach();
    }

    private function deleteClassScoreStudents(ClassScore $classScore)
    {
        $classScore->students()->delete();
    }
}
