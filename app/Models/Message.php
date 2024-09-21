<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function sender()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class, 'message_id');
    }
}
