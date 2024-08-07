<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function exams(){
        return $this->belongsToMany(Exam::class);
    }

    public function classScores(){
        return $this->belongsToMany(ClassScore::class);
    }
}
