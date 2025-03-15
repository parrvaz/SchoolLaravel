<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolGrade extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function school(){
        return $this->belongsTo(School::class);
    }

    public function user(){
        return $this->hasOneThrough(User::class, School::class,'id','user_id','school_id','id');
    }

    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }

    public function teachers(){
        return $this->hasManyThrough(Teacher::class, School::class,'id','school_id','id','id');
    }

    public function exams(){
        return $this->hasMany(Exam::class);
    }

    public function plans(){
        return $this->hasMany(Plan::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Classroom::class);
    }

    public function courseGrades(){
        return $this->hasMany(CourseGrade::class);
    }
}
