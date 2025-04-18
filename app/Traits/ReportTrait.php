<?php

namespace App\Traits;

use App\Http\Requests\Report\AnalysisValidation;
use App\Http\Requests\Report\FilterValidation;
use App\Models\Absent;
use App\Models\Exam;
use App\Models\Student;
use App\Models\StudentExam;
use Illuminate\Support\Facades\DB;
use phpseclib3\Math\BigInteger\Engines\GMP\DefaultEngine;
use function PHPUnit\Framework\isFalse;

trait ReportTrait
{
    public function generalFilter(\Illuminate\Database\Eloquent\Builder $exams, FilterValidation $validation): void
    {
        $exams = $this->globalFilterWhereIn($exams, "exams.type", $validation->types);
        $exams = $this->globalFilterWhereIn($exams, "classroom_exam.classroom_id", $validation->classrooms);
        $exams = $this->globalFilterWhereIn($exams, "exams.course_id", $validation->courses);
        $exams = $this->globalFilterWhereIn($exams, "student_exam.student_id", $validation->students);
        $exams = $this->globalFilterWhereIn($exams, "exams.id", $validation->exams);

        $exams = $this->filterByDate($exams, $validation->startDate, $validation->endDate);
    }

    public function generalFilterAnalysis(\Illuminate\Database\Eloquent\Builder $exams, AnalysisValidation $validation): void
    {
        $exams = $this->globalFilterWhereIn($exams, "classroom_exam.classroom_id", $validation->classrooms);
        $exams = $this->globalFilterWhereIn($exams, "exams.course_id", $validation->courses);
        $exams = $this->globalFilterWhereIn($exams, "student_exam.student_id", $validation->students);
    }

    public function absentMtd($request,$validation){
        $classroomsIds = $validation->classrooms ?? $request->schoolGrade->classrooms()->pluck("id");

        $allAbs = Absent::query();
        $allAbs = self::filterByDate($allAbs,$validation->startDate,$validation->endDate);
        $allAbs = self::globalFilterWhereIn($allAbs,"classroom_id",$classroomsIds);
        $allAbs = $allAbs->groupBy("classroom_id")
            ->select("classroom_id",
                DB::raw('count(*) as total'))
            ->get();

        $classrooms = [];
        foreach ($allAbs as $abs){
            $classrooms[$abs->classroom_id]=$abs->total;
        }

        $absents = Absent::query()
            ->leftJoin("absent_student","absent_student.absent_id","absents.id")
            ->join("students","absent_student.student_id","students.id");
        $absents = self::filterByDate($absents,$validation->startDate,$validation->endDate);
        $absents = self::globalFilterWhereIn($absents,"absents.classroom_id",$classroomsIds);
        $absents = $absents
            ->groupBy("student_id","absents.classroom_id","students.firstName","students.lastName")
            ->select("student_id","absents.classroom_id","students.firstName","students.lastName",
                DB::raw('count(*) as number'))
            ->orderBy(DB::raw('count(*)'),"DESC")
            ->get();

        //absents map
        foreach ($absents as $absent){
            $absent->total = $classrooms[$absent->classroom_id];
            $absent->percent= (int)(($absent->number / $absent->total) * 100 )?? 0;
            $absent->classroomTitle =  $absent->classroom->title;

            if  ($absent->percent < 5)
                $absent->rank = "😓";
            elseif ( $absent->percent < 10)
                $absent->rank = "😢";
            elseif ($absent->percent < 25)
                $absent->rank = "😳";
            elseif ( $absent->percent < 40)
                $absent->rank = "🤯";
            else
                $absent->rank = "😡";

        }


        $absents = $absents->sortByDesc("percent")->values();

        return $absents;
    }

    public function cardMtd($request,$validation){
        $user = auth()->user();
        switch ($user->role) {
            case config("constant.roles.student"):
            case config("constant.roles.parent"):
                $student = $user->student;
                $validation['students'] = [$student->id];
                break;
            case config("constant.roles.teacher"):
                $teacher = $user->teacher;
                $validation = $this->arrayDiffFilter($validation,$teacher->courses,"courses");
                $validation = $this->arrayDiffFilter($validation,$teacher->classrooms,"classrooms");
                break;
        }

        $studentExam = StudentExam::query()
            ->join("exams","exams.id","student_exam.exam_id")
            ->join("classroom_exam", "classroom_exam.exam_id", "exams.id")
            ->join("classrooms","classrooms.id","classroom_exam.classroom_id")
            ->leftJoin('course_fields', function ($join) {
                $join->on('course_fields.course_id', '=', 'exams.course_id')
                    ->where(function ($query) {
                        $query->whereColumn('course_fields.field_id', 'classrooms.field_id')
                            ->orWhereNull('course_fields.field_id');
                    });
            })

            ->where("exams.status",1)
            ->where("exams.school_grade_id",$request->schoolGrade->id)
        ;


        $studentExam = $this->globalFilterWhereIn($studentExam,"exams.type",$validation->types);
        $studentExam = $this->globalFilterWhereIn($studentExam,"classroom_exam.classroom_id",$validation->classrooms);
        $studentExam = $this->globalFilterWhereIn($studentExam,"exams.course_id",$validation->courses);
        $studentExam = $this->globalFilterWhereIn($studentExam,"exams.id",$validation->exams);
        $studentExam = $this->globalFilterWhereIn($studentExam,"student_exam.student_id",$validation->students);
        $studentExam = $this->filterByDate($studentExam,$validation->startDate,$validation->endDate);

        $result = [];

        if (!$validation->isSeparate){
            $studentExam = $studentExam->groupBy(
                "exams.course_id",
                "course_fields.factor",
            );

            $studentExam = $studentExam->select(
                "exams.course_id",
                "course_fields.factor",
                DB::raw("ROUND(AVG(student_exam.scaledScore) / 5,2) as score"),
                DB::raw("ROUND(AVG(student_exam.scaledScore) / 5,2) * factor as wightedScore"),
            );
            $studentExam = $studentExam->get();

            if ($studentExam->count() > 0) {
                $factors = $studentExam->sum("factor");
                $wightedScores = $studentExam->sum("wightedScore");
                $average = round($wightedScores / $factors, 2);
                $result['average'] = $average;
                $result['studentExam'] = $studentExam;
            }
        }else{
            $studentExam = $studentExam->with(['student']);
            $studentExam = $studentExam->groupBy(
                "exams.course_id",
                "course_fields.factor",
                "student_exam.student_id"
            );

            $studentExam = $studentExam->select(
                "exams.course_id",
                "course_fields.factor",
                "student_exam.student_id",

                DB::raw("ROUND(AVG(student_exam.scaledScore) / 5,2) as score"),
                DB::raw("ROUND(AVG(student_exam.scaledScore) / 5,2) * factor as wightedScore"),
            );

            $studentExam = $studentExam
                ->orderBy(
                    Student::select('lastName')
                        ->whereColumn('students.id', 'student_exam.student_id')
                        ->limit(1)
                )
                ->get();

            $courseIds = $studentExam->pluck("course_id")->unique();
            $result['courses'] = $courseIds;
            $students =  $studentExam->groupBy("student_id");

            foreach ($students as $id=>$studentE){

                $factors = $studentE->sum("factor");
                $wightedScores = $studentE->sum("wightedScore");
                $average = round( $wightedScores / $factors,2);
                $result["students"][$id]['average'] = $average;
                $result["students"][$id]['name'] = $average;
                $result["students"][$id]['lastName'] = $average;
                $result["students"][$id]['scores'] = $studentE;
            }

        }



        return $result;
    }

    public function makeAbsentHeaderRows($items,$students,$startRow){
        $headers = collect();
        if($items==null){
            $row = collect(["نام","نام خانوادگی","کلاس"]);
            $headers->add($row);


            foreach ($students as $key=>$student){
                $row = collect();

                $std = Student::find($key);

                $row->add( $std->firstName);
                $row->add( $std->lastName);
                $row->add( $std->classroom->title);
                $headers->add($row);
            }

            $items = $headers;
        }



            return $items;

    }


    public function makeCardHeaderRows($items,$students){
        if($items!=null){
            ($items[0])->add("درس");
            ($items[1])->add("تاریخ شروع");
            ($items[2])->add("تاریخ پایان");


            for ($i=5;$i< count($items) ; $i++)
                ($items[$i])->add(null);

            return $items;
        }
        else{
            $headers =collect();
            $row = collect(["","","درس"]);
            $headers->add($row);
            $row = collect(["","","تاریخ شروع"]);
            $headers->add($row);
            $row = collect(["","","تاریخ پایان"]);
            $headers->add($row);
            $row = collect(["نام","نام خانوادگی","کلاس"]);
            $headers->add($row);

            foreach ($students as $key=>$student){
                $row = collect();

                $std = Student::find($key);

                $row->add( $std->firstName);
                $row->add( $std->lastName);
                $row->add( $std->classroom->title);
                $headers->add($row);
            }

            return $headers;
        }

    }

    public function makeDetailHeaderRows($exams)
    {
        $headers =collect();


        $row = collect(["","","درس"]);
        foreach ($exams as $exam){
            $row->add($exam->course->title);
        }
        $headers->add($row);

        $row = collect(["","","تاریخ"]);
        foreach ($exams as $exam){
            $row->add(self::gToJ($exam->date));
        }
        $headers->add($row);

        $row = collect(["","","حداکثر نمره"]);
        foreach ($exams as $exam){
            $row->add($exam->totalScore);
        }
        $headers->add($row);

        $row = collect(["","","نمره مورد انتظار"]);
        foreach ($exams as $exam){
            $row->add($exam->expected);
        }
        $headers->add($row);

        $row = collect(["","","نوع آزمون"]);
        foreach ($exams as $exam){
            $row->add( config("constant.exams.".$exam->type));
        }
        $headers->add($row);

        $row = collect(["نام","نام خانوادگی","کلاس"]);
        foreach ($exams as $exam){
            $row->add("");
        }
        $headers->add($row);


        return $headers;

    }

    public function makeItemsForExcel($exams, $students)
    {
        $items = $this->makeDetailHeaderRows($exams);

        foreach ($students as $key=>$student){
            $row = collect();

            $std = Student::find($key);

            $row->add( $std->firstName);
            $row->add( $std->lastName);
            $row->add( $std->classroom->title);
            foreach ($exams as $exam){
                $score = $student->where("exam_id",$exam->id)->first()->score ?? null;
                $row->add( $score === 0 ? "0" : $score ) ;
            }
            $items->add($row);
        }

        return $items;
    }

    public function makeItemsCardExcel($items,$students,$result,$startStd){
        $items = $this->makeCardHeaderRows($items,$students);

        foreach ($result["courses"] as $course){
            ($items[0])->add($course->title);
            ($items[1])->add("***");
            ($items[2])->add("***");
            ($items[$startStd -1])->add("");
            $i= $startStd;
            foreach ($students as $key=>$student){
                $e = $result["students"][$key] ?? null;

                $score = $e != null ? $e["scores"]->where("course_id",$course->id)->first()->score ?? null : null;
                ($items[$i])->add( $score === 0 ? "0" : $score ) ;
                $i++;
            }
        }
        return $items;
    }

    public function allExamsScores($request,$validation){
        $exams= Exam::query()
            ->where("exams.school_grade_id", $request->schoolGrade->id)
            ->where("exams.status",1);

        $exams = $this->filterByDate($exams,$validation->startDate,$validation->endDate);
        $exams = $this->globalFilterWhereIn($exams,"id",$validation->exams);
        $exams =$exams
            ->orderBy("exams.course_id")
            ->orderBy("date")
            ->get();

        $students = StudentExam::query()->
        whereHas('exam', function ($query)use($request) {
            return $query->where('school_grade_id', $request->schoolGrade->id);
        });
        $students = $this->globalFilterRelationWhereIn($students,"classroom_id",$validation->classrooms,"exam");
        $students = $this->globalFilterWhereIn($students,"student_exam.student_id",$validation->students);
        $students =$students
            ->orderBy("student_id")
            ->get()
            ->groupBy("student_id")
        ;

        return [$exams,$students];
    }
}
