<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGrade extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }

    public function teachers(){
        return $this->hasMany(Teacher::class);
    }

    public function assistants(){
        return $this->hasMany(Assistant::class);
    }

    public function exams(){
        return $this->hasMany(Exam::class);
    }

    public function classScores(){
        return $this->hasMany(ClassScore::class);
    }

    public function tests(){
        return $this->hasMany(Test::class);
    }

    public function plans(){
        return $this->hasMany(Plan::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Classroom::class);
    }
}
