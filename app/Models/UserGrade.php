<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGrade extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }

    public function teachers(){
        return $this->hasManyThrough(Teacher::class, User::class,'id','user_id','user_id','id');
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
