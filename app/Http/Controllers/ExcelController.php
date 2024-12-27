<?php

namespace App\Http\Controllers;

use App\Exports\GeneralExcelExport;
use App\Http\Requests\Report\FilterValidation;
use App\Models\Exam;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function general(Request $request,FilterValidation $validation){

        [$exams,$students] =$this->allExamsScores($request,$validation);
        $items = $this->makeItemsForExcel($exams,$students);


        $validation->title = $validation->title ?? "اکسل کامل";
        $name = $validation->title  . ".xlsx" ;
        return Excel::download(new GeneralExcelExport($items), $name);
    }
}
