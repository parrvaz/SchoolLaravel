<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function schoolGrade(){
        return $this->belongsTo(SchoolGrade::class);
    }

    public function field(){
        return $this->belongsTo(Field::class);
    }

    public function schedules(){
        return $this->hasMany(Schedule::class);
    }

    public function exams(){
        return $this->hasMany(Exam::class);
    }

    public function homework(){
        return $this->belongsToMany(Homework::class);
    }



    public function getFieldTitleAttribute(){
        return $this->field->title;
    }
}
