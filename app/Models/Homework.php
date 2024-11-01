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

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function files(){
        return $this->hasMany(FileHomework::class);
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

}
