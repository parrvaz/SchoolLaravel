<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTestCourse extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="student_test_course";

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
