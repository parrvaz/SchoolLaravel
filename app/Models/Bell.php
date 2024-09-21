<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bell extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function schedules(){
        return $this->hasMany(Schedule::class);
    }

    public function absents(){
        return $this->hasMany(Absent::class);
    }

    public function course(){
        return $this->hasOneThrough(Course::class, Schedule::class,'bell_id','id','id','course_id');
    }

    public function GetTeacher($classroomId){
        ClassCourseTeacher::weher("classroom_id",$classroomId)->where("course_id",$this->course->id)->first()->techer;

    }
}
