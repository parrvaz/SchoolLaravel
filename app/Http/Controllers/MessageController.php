<?php

namespace App\Http\Controllers;

use App\Http\Requests\Messag\MessageValidation;
use App\Http\Resources\Messages\InboxCollection;
use App\Http\Resources\Messages\MessageCollection;
use App\Models\Message;
use App\Models\MessageRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Musonza\Chat\Facades\ChatFacade as Chat;

class MessageController extends Controller
{
    public function send(Request $request, MessageValidation $validation){

        return DB::transaction(function () use($request,$validation) {
            // ایجاد پیام جدید
            $message = Message::create([
                'user_id' => auth()->user()->id, // فرستنده پیام
                'subject' => $validation->subject,
                'body' => $validation->body,
            ]);

            // ثبت گیرندگان پیام
            foreach ($validation->recipients as $recipient_id) {
                MessageRecipient::create([
                    'message_id' => $message->id,
                    'user_id' => $recipient_id, // شناسه گیرنده
                ]);
            }

            return $this->successMessage();
        });
    }

    public function inbox()
    {
        $inbox = MessageRecipient::where('user_id', auth()->user()->id)
            ->with('message') // پیام‌های مربوط به گیرنده
            ->orderBy('created_at', 'desc')
            ->get();

        return new InboxCollection($inbox);
    }

    public function markAsRead($userGrade ,MessageRecipient $messageRecipient)
    {
        if ($messageRecipient) {
            $messageRecipient->update([
                "isRead"=>true
            ]);
            return $this->successMessage();
        }
        return $this->error();
    }

    public function sentMessages()
    {
        $sentMessages = Message::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return new MessageCollection($sentMessages);
    }
}