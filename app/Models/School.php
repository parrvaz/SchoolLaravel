<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function bells(){
        return $this->hasMany(Bell::class);
    }

    public function grades(){
        return $this->hasMany(SchoolGrade::class);
    }

    public function teachers(){
        return $this->belongsToMany(Teacher::class);
    }
}
