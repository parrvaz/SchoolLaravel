<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function classrooms(){
        return $this->belongsToMany(Classroom::class);
    }

    public function students(){
        return $this->hasMany(StudentHomework::class);
    }

    public function allStudents()
    {
        return $this->hasManyThrough(
            Student::class,
            ClassroomHomework::class,
            'homework_id',       // Foreign key on the classroom_homework table...
            'classroom_id',      // Foreign key on the students table...
            'id',                // Local key on the homework table...
            'classroom_id'       // Local key on the classroom_homework table...
        );
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function allFiles(){
        return $this->hasMany(FileHomework::class);
    }

    public function files(){
        return $this->hasMany(FileHomework::class)->where("type",config("constant.files.files"));
    }

    public function photos(){
        return $this->hasMany(FileHomework::class)->where("type",config("constant.files.photos"));
    }

    public function voices(){
        return $this->hasMany(FileHomework::class)->where("type",config("constant.files.voices"));
    }


    public function pdfs(){
        return $this->hasMany(FileHomework::class)->where("type",config("constant.files.pdfs"));
    }

    public function studentHomework($stdId){
        return $this->hasOne(StudentHomework::class)->where("student_id",$stdId);
    }
}
