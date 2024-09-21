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

    public function modelHasRole(){
        return $this->hasOne(ModelHasRole::class,"idInRole");
    }

    public function user(){
        return $this->hasOneThrough(User::class, ModelHasRole::class,'idInRole','id','id','model_id');
    }

    public function parentUser(){
//        return User::where("phone",$this->fatherPhone);
        return $this->hasOne(User::class, 'phone','fatherPhone');
    }

    public function getNameAttribute(){
       return $this->firstName ." ". $this->lastName;
    }

    public function getClassroomTitleAttribute(){
        return $this->classroom->title;
    }


}
