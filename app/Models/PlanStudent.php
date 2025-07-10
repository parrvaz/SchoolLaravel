<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanStudent extends Model
{
    protected $guarded=[];

    protected $table="plan_student";

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
