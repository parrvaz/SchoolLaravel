<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function student(){
        return $this->belongsTo(Student::class,"id","idInRole");
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class,"id","idInRole");
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }
}
