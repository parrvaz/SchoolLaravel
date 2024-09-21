<?php

namespace App\Http\Controllers;

use App\Http\Requests\Messag\MessageValidation;
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

        return response()->json($inbox);
    }

    public function markAsRead($userGrade ,MessageRecipient $messageRecipient)
    {
        $recipient = MessageRecipient::where('message_id', $message_id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if ($recipient) {
            $recipient->is_read = true;
            $recipient->save();
            return response()->json(['message' => 'پیام به عنوان خوانده شده علامت‌گذاری شد']);
        }

        return response()->json(['error' => 'پیام یافت نشد'], 404);
    }

    public function sentMessages()
    {
        $sentMessages = Message::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sentMessages);
    }
}
