<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function bell(){
        return $this->belongsTo(Bell::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

}
