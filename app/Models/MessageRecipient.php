<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    // گیرنده پیام به یک کاربر خاص مرتبط است
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
