<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function getNameAttribute(){
       return $this->firstName ." ". $this->lastName;
    }

    public function getClassroomTitleAttribute(){
        return $this->classroom->title;
    }
}
