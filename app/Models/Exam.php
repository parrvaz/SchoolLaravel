<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function contents(){
        return $this->belongsToMany(Content::class);
    }

    public function students(){
        return $this->hasMany(StudentExam::class);
    }

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function getClassroomTitleAttribute(){
        return $this->classroom->title;
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function getCourseNameAttribute(){
        return $this->course->name;
    }

}
