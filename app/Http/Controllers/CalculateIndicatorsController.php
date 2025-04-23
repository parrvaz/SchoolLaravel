<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\AnalysisValidation;
use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Reports\Progress\ProgressCollection;
use App\Models\Exam;
use App\Models\Student;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MathPHP\Statistics\Descriptive;

class CalculateIndicatorsController extends Controller
{

    public function calculateExam($exam): void
    {
        DB::transaction(function () use($exam) {
            $this->calculateAverage($exam);
            $this->calculateBalance1($exam);
            $this->calculateBalance2($exam);
        });
            $this->calculateAverageBalance1($exam);
            $this->calculateAverageBalance2($exam);

    }


    public function getRegression(AnalysisValidation $validation){
        $exams  = $this->getPoints($validation);
        $formattedData = new Collection();
        foreach ($exams as $exam) {
            $dayOfYear=   self::GetDayOfYear($exam->date);
            $formattedData->push([$dayOfYear, $exam['balance1']]);
        }
        if ( count($formattedData) < 2)
            $this->throwExp("dataNotEnough","422");
        $regression = $this->regression($formattedData);

        return [$formattedData,$regression];
    }







    //******************************** PRIVATE FUNCTIONS *****************************************
    private function regression($data){
        $xs = $data->pluck(0);
        $ys = $data->pluck(1);
        $avgX=  $xs->average();
        $avgY=  $ys->average();

        $sumNumerator = 0;
        $sumDenominator = 0;
        foreach ($data as $point){
            $sumNumerator+= ( $point[0] - $avgX )*( $point[1] - $avgY );
            $sumDenominator+= ( $point[0] - $avgX )*( $point[0] - $avgX );
        }

        return (float) $sumNumerator / $sumDenominator;

    }
    private function getPoints(AnalysisValidation $validation){
        $exams= Exam::query()
            ->where("exams.school_grade_id", request()->schoolGrade->id)
            ->where("exams.status",1)
            ->join("student_exam","exams.id","student_exam.exam_id")
            ->join("classroom_exam", "classroom_exam.exam_id", "exams.id")
            ->join("classrooms","classrooms.id","classroom_exam.classroom_id")
            ->leftJoin('course_fields', function ($join) {
                $join->on('course_fields.course_id', '=', 'exams.course_id')
                    ->where(function ($query) {
                        $query->whereColumn('course_fields.field_id', 'classrooms.field_id')
                            ->orWhereNull('course_fields.field_id');
                    });
            })
            ->whereNotNull("student_exam.balance1")
        ;

        $this->generalFilterAnalysis($exams, $validation);
        $exams =  $exams->orderBy("date")
            ->groupBy("exams.date")
            ->select(
                "exams.date",
                DB::raw("MIN(exams.id) as id"),
                DB::raw("ROUND(AVG(student_exam.balance1)) as balance1"),
                DB::raw("ROUND(AVG(student_exam.balance2)) as balance2"),
                DB::raw("ROUND(AVG(scaledScore))"),
                DB::raw("GROUP_CONCAT(DISTINCT exams.course_id) as course_ids") // اضافه کردن course_ids

            );
        $exams= $exams->get();

        return $exams;

    }
    private function calculateAverage($exam){
        $exam->update([
            "average"=> $exam->students->sum("scaledScore") / $exam->students->count()
        ]);
    }

    private function calculateBalance1($exam){
        $studentExams = $exam->students;
        $scoresArray =$studentExams->pluck("scaledScore")->toArray();
        $stdDev =  Descriptive::standardDeviation($scoresArray);
        if ($stdDev==0)
            return;
        $average = $exam->average;


        $cases = '';
        $ids = [];

        foreach ($studentExams as $stdExam) {
            $id =  $stdExam->id;
            $balance = $this->formulaBalance1($stdExam->scaledScore,$average,$stdDev);
            $cases .= "WHEN $id THEN $balance ";
            $ids[] = $id;
        }

        $ids = implode(',', $ids);

        DB::statement("
        UPDATE student_exam
        SET balance1 = CASE id
        $cases
        END
        WHERE id IN ($ids)
        ");
    }

    private function calculateBalance2($exam){
        $studentExams = $exam->students;
        $totalScore = $exam->totalScore;
        $expected = $exam->expected;
        if ($totalScore==0 || $expected==0)
            return;

        $cases = '';
        $ids = [];

        foreach ($studentExams as $stdExam) {
            $id =  $stdExam->id;
            $balance = $this->formulaBalance2($stdExam->score,$totalScore,$expected);
            $cases .= "WHEN $id THEN $balance ";
            $ids[] = $id;
        }

        $ids = implode(',', $ids);

        DB::statement("
        UPDATE student_exam
        SET balance2 = CASE id
        $cases
        END
        WHERE id IN ($ids)
        ");
    }

    private function calculateAverageBalance1($exam): void
    {
        $avg = StudentExam::where('exam_id', $exam->id)->avg('balance1');
        $exam->update([
            "balance1"=> $avg
        ]);
    }

    private function calculateAverageBalance2($exam): void
    {
        $avg = StudentExam::where('exam_id', $exam->id)->avg('balance2');
        $exam->update([
            "balance2"=> $avg
        ]);
    }

    private function formulaBalance1($score,$average,$dev){
       return ((($score-$average)/$dev) *1000)+ 5000;
    }

    private function formulaBalance2($score,$totalScore,$expected){
        return ( $score * (1/$totalScore) *(17/$expected) * 1000)+ 5000;
    }
}
