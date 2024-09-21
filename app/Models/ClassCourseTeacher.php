<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassCourseTeacher extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table = 'class_course_teacher';

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
}
