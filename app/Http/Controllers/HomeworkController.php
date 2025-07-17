<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\HomeworkStoreValidation;
use App\Http\Requests\Exam\ScoreStoreValidation;
use App\Http\Resources\Homework\HomeworkCollection;
use App\Http\Resources\Homework\HomeworkResource;
use App\Http\Resources\Homework\ScoreHomeworkCollection;
use App\Http\Resources\Homework\ScoreHomeworkResource;
use App\Models\FileHomework;
use App\Models\Homework;
use App\Models\Student;
use App\Models\StudentHomework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeworkController extends Controller
{
    public function store(Request $request,HomeworkStoreValidation $validation){
        return DB::transaction(function () use($request,$validation) {
            $homework  = Homework::create([
                "school_grade_id"=>$request->schoolGrade->id,
                "user_id"=>auth()->user()->id,
                'course_id'=>$validation->course_id,
                'expected'=>$validation->expected,
                'score'=>$validation->score,
                'isFinal'=>$validation->isFinal ?? false,
                'date'=>self::jToG($validation->date),
                'title'=>$validation->title,
                'description'=>$validation->description,
                'link'=>$validation->link,
            ]);
            $this->saveFilesOfHomeWork($homework, $validation, $request);
            return $this->successMessage();

        });
    }

    public function scoreStore(ScoreStoreValidation $validation,$schoolGrade,StudentHomework $studentHomework){
        return DB::transaction(function () use($studentHomework,$validation) {
            $studentHomework->timestamps = false;
            $studentHomework->score = $validation->score;
            $studentHomework->scaledScore =  round((( $validation->score*100 )/ $studentHomework->homework->score),2) ;
            $studentHomework->save();
            $studentHomework->timestamps = true;
            return $this->successMessage();
        });
    }

    public function setFinal(Request $request,$schoolGrade,Homework $homework){
        if (!$this->checkAccess($request,$homework))
            return $this->error("permissionForUser",403);

        return DB::transaction(function () use($homework) {
            $homework->timestamps = false;
            $homework->isFinal = !$homework->isFinal;
            $homework->save();
            $homework->timestamps = true;
            return $this->successMessage();
        });
    }

    public function setZero(Request $request,$schoolGrade,Homework $homework){
        if (!$this->checkAccess($request,$homework))
            return $this->error("permissionForUser",403);

        return DB::transaction(function () use($homework) {
            $studentIds = $homework->students()->pluck("student_id")->toArray();
            $classroomIds = $homework->classrooms()->pluck("classrooms.id");

            $allStdInClass = Student::whereIn("classroom_id",$classroomIds)->pluck("id")->toArray();

            $diffStudents = array_diff($allStdInClass,$studentIds);


            $items = [];
            foreach ($diffStudents as $std){
                $items[] = [
                    "student_id"=> $std ,
                    'homework_id'=>$homework->id,
                    'score'=>0,
                    'scaledScore'=>0,
                    'solution'=>null,
                    'note'=>null,
                ];
            }
            StudentHomework::insert($items);
            return $this->successMessage();
        });
    }




    public function show(Request $request){

        $homework = Homework::query()->where("school_grade_id",$request->schoolGrade->id);

        if (auth()->user()->role == config("constant.roles.teacher")){
            $teacher = auth()->user()->teacher;
            $classrooms = $teacher->classrooms->pluck("id")->unique();
            $courses = $teacher->courses->pluck("id")->unique();
            $homework = $this->globalFilterRelationWhereIn($homework,"classrooms.id",$classrooms,"classrooms");
            $homework = $this->globalFilterWhereIn($homework,"course_id",$courses);
        }
        $homework = $homework->get();

        return new HomeworkCollection($homework);
    }

    public function showSingle(Request $request,$schoolGrade, Homework $homework){
        if (!$this->checkAccess($request,$homework))
            return $this->error("permissionForUser",403);
        return new HomeworkResource($homework);
}

    public function update(Request $request,HomeworkStoreValidation $validation,$schoolGrade,Homework $homework){

        if (auth()->user()->role == config("constant.roles.teacher") && $homework->user_id != auth()->user()->id )
            return $this->error("permissionForUser",403);

        return DB::transaction(function () use($request,$validation,$homework) {
            $homework->classrooms()->detach();
            $this->deleteGroupFile($homework->allFiles()->pluck("file"));
            $homework->allFiles()->delete();

            $homework->update([
                'course_id'=>$validation->course_id,
                'expected'=>$validation->expected,
                'score'=>$validation->score,
                'isFinal'=>$validation->isFinal ?? false,
                'date'=>self::jToG($validation->date),
                'title'=>$validation->title,
                'description'=>$validation->description,
                'link'=>$validation->link,
            ]);
            $this->saveFilesOfHomeWork($homework, $validation, $request);
            return $this->successMessage();
        });
    }

    public function delete($schoolGrade,Homework $homework){
        if (auth()->user()->role == config("constant.roles.teacher") && $homework->user_id != auth()->user()->id )
            return $this->error("permissionForUser",403);

        return DB::transaction(function () use($homework) {
            $homework->classrooms()->detach();
            $this->deleteGroupFile($homework->allFiles()->pluck("file"));
            $homework->allFiles()->delete();
            $homework->delete();

            return $this->successMessage();
        });
    }


    public function showStudent(Request $request,$schoolGrade, Homework $homework){
        if (!$this->checkAccess($request,$homework))
            return $this->error("permissionForUser",403);
        return new ScoreHomeworkCollection($homework->students);
    }

    public function showScore(Request $request,$schoolGrade, Homework $homework){
       if (!$this->checkAccess($request,$homework))
           return $this->error("permissionForUser",403);

        $allStudents = $homework->students->merge($homework->notSubmittedStudents);
        $homework->allStudentTmp  =$allStudents;
        return new ScoreHomeworkResource($homework);
    }



    private function saveFilesOfHomeWork($homework, $validation, $request): void
    {
        $homework->classrooms()->attach($validation->classrooms);

        $files = [];
        $files = $this->fileHandler($request, $homework->id, "voices", $files);

        $files = $this->fileHandler($request, $homework->id, "files", $files);

//        $files = $this->fileHandler($request, $homework->id, "photos", $files);
//        $files = $this->fileHandler($request, $homework->id, "pdfs", $files);

        FileHomework::insert($files);
    }

    private function fileHandler($request,$id,$name,$files){
        $filePaths = $this->saveGroupFile($request,"teachers/homework",$name);
        foreach ($filePaths as $path){
            $files[]=[
                "homework_id"=>$id,
                "file"=>$path,
                "type"=>config("constant.files.".$name) ,
            ];
        }
        return $files;
    }


    private function checkAccess($request,$homework){
        if ($homework->school_grade_id != $request->schoolGrade->id)
            return 0;

        $user = auth()->user();
        if ($user->role == config("constant.roles.teacher"))
        {
            if ( !in_array(  $homework->course_id,$user->teacher->courses->pluck("id")->toArray() ))
                return 0;

            $common = array_intersect($homework->classrooms->pluck("id")->toArray(), $user->teacher->classrooms->pluck("id")->toArray());
            if (empty($common))
                return 0;
        }
        return 1;
    }

}
