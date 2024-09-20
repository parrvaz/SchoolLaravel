<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageBox\MessageBoxValidation;
use Illuminate\Http\Request;
use Musonza\Chat\Facades\ChatFacade as Chat;

class MessageBoxController extends Controller
{
    public function send(Request $request,MessageBoxValidation $validation){

//        $senderUser= auth()->user();
//        $resiverUser = $validation->user_id
//
//        $conversation = Chat::createConversation([$senderUser, $resiverUser]);
//
//        $message = Chat::message($validation->messages())
//            ->from($senderUser) // فرستنده پیام
//            ->to($conversation) // مکالمه‌ای که پیام به آن ارسال می‌شود
//            ->send();
//
//        return $validation;

    }
}
