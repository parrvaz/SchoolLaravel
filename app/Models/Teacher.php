<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $guarded=[];



    public function modelHasRole(){
        return $this->hasOne(ModelHasRole::class,"idInRole");
    }


    public function user(){
        return $this->hasOneThrough(User::class, ModelHasRole::class,'idInRole','id','id','model_id');
    }


    public function getNameAttribute(){
        return $this->firstName ." ". $this->lastName;
    }
}
