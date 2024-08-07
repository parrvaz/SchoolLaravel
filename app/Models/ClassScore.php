<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassScore extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function contents(){
        return $this->belongsToMany(Content::class);
    }

    public function students(){
        return $this->hasMany(StudentClassScore::class);
    }
}
