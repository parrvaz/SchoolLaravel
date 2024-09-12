<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function subs(){
        return $this->hasMany(MenuItem::class,"parent_id","id");
    }
}
