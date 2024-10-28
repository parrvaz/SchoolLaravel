<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Grade\ExamCreateCollection;
use App\Http\Resources\Grade\ExamCreateResource;
use App\Http\Resources\Reports\AllCountResource;
use App\Http\Resources\Exam\ExamCollection;
use App\Http\Resources\Student\StudentCollection;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentExam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{

    public function operation(Request $request){

        $studentExam = StudentExam::all();
        foreach ($studentExam as $std){
            $std->update([
                "scaledScore"=> round((($std->score*100 )/ $std->exam->totalScore),2)
            ]);
        }

        return $this->successMessage();
    }


}
