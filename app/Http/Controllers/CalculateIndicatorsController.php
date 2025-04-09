<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            $this->calculateAverageBalance1($exam);
            $this->calculateAverageBalance2($exam);
        });
    }

    public function calculateAverage($exam){
        $exam->update([
            "average"=> $exam->students->sum("scaledScore") / $exam->students->count()
        ]);
    }

    public function calculateBalance1($exam){
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

    public function calculateBalance2($exam){
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

    public function calculateAverageBalance1($exam): void
    {
        $exam->update([
            "balance1"=> $exam->students->sum("balance1") / $exam->students->count()
        ]);
    }

    public function calculateAverageBalance2($exam): void
    {
        $exam->update([
            "balance2"=> $exam->students->sum("balance2") / $exam->students->count()
        ]);
    }

    public function regression(){

    }


    private function formulaBalance1($score,$average,$dev){
       return ((($score-$average)/$dev) *1000)+ 5000;
    }

    private function formulaBalance2($score,$totalScore,$expected){
        return ( $score * (1/$totalScore) *(17/$expected) * 1000)+ 5000;
    }
}
