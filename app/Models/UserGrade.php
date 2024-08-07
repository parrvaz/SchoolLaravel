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

    public function exams(){
        return $this->hasMany(Exam::class);
    }

    public function classScores(){
        return $this->hasMany(ClassScore::class);
    }
}
