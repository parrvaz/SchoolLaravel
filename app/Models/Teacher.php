<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $guarded=[];



    public function modelHasRole(){
        return $this->hasOne(ModelHasRole::class,"idInRole");
    }


    public function user(){
        return $this->hasOneThrough(User::class, ModelHasRole::class,'idInRole','id','id','model_id');
    }

    public function school(){
        return $this->belongsToMany(School::class);
    }

    public function classCourses(){
        return $this->hasMany(ClassCourseTeacher::class);
    }


    public function classCoursesSchool(SchoolGrade $schoolGrade){
        return $this->hasMany(ClassCourseTeacher::class)->whereIn("classroom_id",$schoolGrade->classrooms->pluck("id"));
    }

    public function courses(){
        return $this->hasManyThrough(Course::class, ClassCourseTeacher::class,'teacher_id','id','id','course_id');

    }

    public function classrooms(){
        return $this->hasManyThrough(Classroom::class, ClassCourseTeacher::class,'teacher_id','id','id','classroom_id');

    }

    public function getNameAttribute(){
        return $this->firstName ." ". $this->lastName;
    }
}
