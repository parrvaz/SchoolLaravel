<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function coursePlans(){
        return $this->hasMany(CoursePlan::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class);
    }

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }
}
