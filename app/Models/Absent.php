<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absent extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class);
    }

    public function absentStudents(){
        return $this->hasMany(AbsentStudent::class);
    }

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function bell(){
        return $this->belongsTo(Bell::class);
    }



}
