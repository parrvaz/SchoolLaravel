<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePlan extends Model
{
    use HasFactory;
    protected $table="course_plan";
    protected $guarded=[];


    public function plan(){
        return $this->belongsTo(Plan::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
