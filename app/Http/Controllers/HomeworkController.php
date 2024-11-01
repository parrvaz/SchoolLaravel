<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\HomeworkStoreValidation;
use App\Http\Resources\Homework\HomeworkCollection;
use App\Models\FileHomework;
use App\Models\Homework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeworkController extends Controller
{
    public function store(Request $request,HomeworkStoreValidation $validation){
        return DB::transaction(function () use($request,$validation) {
            $homework  = Homework::create([
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
            $homework->classrooms()->attach($validation->classrooms);

            $files=[];
            $files=$this->fileHandler($request,$homework->id,"photos",$files);
            $files=$this->fileHandler($request,$homework->id,"voices",$files);
            $files=$this->fileHandler($request,$homework->id,"pdfs",$files);

            FileHomework::insert($files);
            return $this->successMessage();
        });
    }

    public function show(Request $request){
        $homework = Homework::query();

        if (auth()->user()->role == config("constant.roles.teacher")){
            $teacher = auth()->user()->teacher;
            $classrooms = $teacher->classrooms->pluck("id")->unique();
            $courses = $teacher->courses->pluck("id")->unique();
            $homework = $this->globalFilterRelationWhereIn($homework,"classrooms.id",$classrooms,"classrooms");
            $homework = $this->globalFilterWhereIn($homework,"course_id",$courses);
        }else{
            $homework = $this->globalFilterRelation($homework,"user_grade_id",$request->userGrade->id,"classrooms");
        }
        $homework = $homework->get();

        return new HomeworkCollection($homework);
    }

//    public function update(Request $request,HomeworkStoreValidation $validation,$userGrade,Homework $homework){
//        return DB::transaction(function () use($request,$validation,$homework) {
//
//            $homework->classrooms()->detach();
//            $homework->files()->delete();
//
//            $homework  = Homework::create([
//                "user_id"=>auth()->user()->id,
//                'course_id'=>$validation->course_id,
//                'expected'=>$validation->expected,
//                'score'=>$validation->score,
//                'isFinal'=>$validation->isFinal ?? false,
//                'date'=>self::jToG($validation->date),
//                'title'=>$validation->title,
//                'description'=>$validation->description,
//                'link'=>$validation->link,
//            ]);
//            $homework->classrooms()->attach($validation->classrooms);
//
//            $files=[];
//            $files=$this->fileHandler($request,$homework->id,"photos",$files);
//            $files=$this->fileHandler($request,$homework->id,"voices",$files);
//            $files=$this->fileHandler($request,$homework->id,"pdfs",$files);
//
//            FileHomework::insert($files);
//            return $this->successMessage();
//        });
//    }



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
}
