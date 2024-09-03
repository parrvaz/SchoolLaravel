<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function courses(){
        return $this->hasMany(TestCourse::class);
    }

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function getClassroomTitleAttribute(){
        return $this->classroom->title;
    }
}
