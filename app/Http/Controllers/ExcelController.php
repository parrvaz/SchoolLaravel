<?php

namespace App\Http\Controllers;

use App\Exports\GeneralExcelExport;
use App\Http\Requests\Report\FilterValidation;
use App\Models\Course;
use App\Models\Exam;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\isFalse;

class ExcelController extends Controller
{
    public function general(Request $request,FilterValidation $validation){

        [$exams,$students] =$this->allExamsScores($request,$validation);

        $items=[];
        if ($validation->detail){
            $items = $this->makeItemsForExcel($exams,$students);
        }
        if ($validation->card){
            $validation['isSeparate'] = 1;
            $result = $this->cardMtd($request,$validation);
            $courseNames = Course::whereIn("id",$result["courses"])->get();
            $result["courses"] = $courseNames;

            //add to items
            $items = $this->makeCardHeaderRows($items,$students);

            foreach ($result["courses"] as $course){
                ($items[0])->add($course->title);
                ($items[1])->add("***");
                ($items[2])->add("***");
                $i= $validation->detail ? 6 : 4;
                foreach ($students as $key=>$student){
                    $e = $result["students"][$key] ?? null;

                    $score = $e != null ? $e["scores"]->where("course_id",$course->id)->first()->score ?? null : null;
                    ($items[$i])->add( $score === 0 ? "0" : $score ) ;
                    $i++;
                }
            }


        }

        $validation->title = $validation->title ?? "اکسل کامل";
        $name = $validation->title  . ".xlsx" ;
        return Excel::download(new GeneralExcelExport($items), $name);
    }
}
