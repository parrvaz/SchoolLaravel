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
           $items =  $this->makeItemsCardExcel($items,$students,$result,$validation->detail ? 6 : 4);
        }

        if ($validation->absent){
            $absents = $this->absentMtd($request,$validation);
            $startRow= ($validation->detail == 1) ? 5 : ($validation->card ? 3 : 0);
            $items = $this->makeAbsentHeaderRows($items,$students, $startRow );
            $validation->absentNumber ? ($items[$startRow])->add("تعداد زنگ غیبت") : null ;
            $validation->absentTotal ?  ($items[$startRow])->add("تعداد زنگ های ثبت شده") : null ;
            $validation->absentPercent ? ($items[$startRow])->add("درصد غیبت") : null ;

            $i=$startRow+1;
            foreach ($students as $key=>$student){
                    $student = $absents->where("student_id",$key)->first();
                    if($student != null) {
                        $validation->absentNumber ?  ($items[$i])->add($student->number): null ;
                        $validation->absentTotal ? ($items[$i])->add($student->total): null ;
                        $validation->absentPercent ? ($items[$i])->add($student->percent): null ;
                    }
                $i++;
            }
        }

        $validation->title = $validation->title ?? "اکسل سفارشی";
        $name = $validation->title  . ".xlsx" ;
        return Excel::download(new GeneralExcelExport($items), $name);
    }
}
