<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded=[];

    public function contents(){
        return $this->hasMany(Content::class);
    }

    public function schedules(){
        return $this->hasMany(Schedule::class);
    }

    public function classTeachers(){
        return $this->hasMany(ClassCourseTeacher::class);
    }

    public function fields(){
        return $this->hasMany(CourseField::class);
    }
}
