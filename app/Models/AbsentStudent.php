<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsentStudent extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="absent_student";
    public $timestamps = false;

    public function absent(){
        return $this->belongsTo(Absent::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
