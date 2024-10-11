<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseField extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function field(){
        return $this->belongsTo(Field::class);
    }
}
