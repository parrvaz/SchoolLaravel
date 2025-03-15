<?php

namespace App\Http\Controllers;

use App\Http\Requests\Messag\MessageValidation;
use App\Http\Resources\Messages\InboxCollection;
use App\Http\Resources\Messages\MessageCollection;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function send(Request $request, MessageValidation $validation){
        $type = ($validation->type=="sms" ?? 1) ? 2 :1;

        $role = auth()->user()->role;
        switch ($role){
            case config("constant.roles.parent"):
                return $this->error("permissionForUser",403);
                break;
            case config("constant.roles.student"):
                $users = User::whereIn("id", $validation->recipients)->get();
                foreach ($users as $user){
                    if ($user->role != config("constant.roles.assistant") && $user->role!= config("constant.roles.manager"))
                        return $this->error("permissionForUser",403);
                }
                break;
        }

        return DB::transaction(function () use($request,$validation,$type) {

            // ایجاد پیام جدید
            $message = Message::create([
                'user_id' => auth()->user()->id, // فرستنده پیام
                'subject' => $validation->subject,
                'body' => $validation->body,
                'type'=>$type,
            ]);
                // ثبت گیرندگان پیام
                foreach ($validation->recipients as $recipient_id) {
                    MessageRecipient::create([
                        'message_id' => $message->id,
                        'user_id' => $recipient_id, // شناسه گیرنده
                    ]);
                }

            if ($type==2){
                $usersPhone = User::whereIn("id",$validation->recipients)->pluck("phone");
                $phones = "";
                foreach ($usersPhone as $phone)
                    $phones = $phones . $phone . ",";
                $phones = substr_replace($phones, '', -1);
                (new SMSController())->sendMessage($validation->body,$phones);
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

    public function markAsRead($schoolGrade ,MessageRecipient $messageRecipient)
    {
        if ($messageRecipient) {
            $messageRecipient->update([
                "isRead"=>true
            ]);
            return;
        }else
            return $this->error();
    }

    public function sentMessages()
    {
        $role = auth()->user()->role;
        if ($role ==config("constant.roles.parent"))
                return $this->error("permissionForUser",403);
        $sentMessages = Message::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return new MessageCollection($sentMessages);
    }
}
