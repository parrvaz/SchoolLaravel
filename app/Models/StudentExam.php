<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="student_exam";

    public function student(){
        return $this->belongsTo(Student::class);
    }

}
