<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;

class SMSController extends Controller
{
    public function sendMessage($message,$phones){
        $url = "https://portal.amootsms.com/rest/SendSimple";
        $url = $url."?"."Token=".urlencode(config("constant.SMS.token"));
        $nowIran = new DateTime('now', new DateTimeZone('IRAN'));
        $url = $url."&"."SendDateTime=".urlencode($nowIran->format('c'));
        $url = $url."&"."SMSMessageText=".urlencode($message);
        $url = $url."&"."LineNumber=public";
        $url = $url."&"."Mobiles=".$phones;
        $json = file_get_contents($url);
    }

    // ✅ 1. ارسال کد تأیید (OTP) به شماره تلفن
    public function sendOtp($otp,$phone)
    {
        $url = "https://portal.amootsms.com/rest/SendQuickOTP";
        $url = $url."?"."Token=".urlencode(config("constant.SMS.token"));
        $url = $url."&"."Mobile=".$phone;
        $url = $url."&"."CodeLength=".config("constant.SMS.otpLength");
        $url = $url."&"."OptionalCode=".$otp;


        $json = file_get_contents($url);
    }



    public function UserInformationSms($user){
        $message = "اطلاعات کاربری شما در سامانه پیکت"."\n"
            ."نام کاربری: ". $user->phone."\n"
            . "رمز عبور: ".$user->roleOfUser->nationalId
            . "\n\n"
            . "در اولین فرصت نسبت به تغییر رمز خود اقدام نمایید"."\n"
            . "لینک ورود"."\n"
            . env("APP_URL")."/api/login";

        $this->sendMessage($message,$user->phone);
    }

    public function UserAddInList($user){
        $roleName = config("constant.roleName.".$user->role) ;
        $schoolName = $user->roleOfUser->school->last()->title;

        $message= "شما به عنوان ".$roleName." به مدرسه ". $schoolName. " اضافه شدید.";
        $this->sendMessage($message, $user->phone);
    }


    public function UserDeleteFromList($phone,$school){
        $message = "شما از لیست مدرسه ".$school." حذف شدید.";
        $this->sendMessage($message,$phone);
    }

    public static function calculateMessagePrice($message,$phones){
        $url = "https://portal.amootsms.com/rest/CalculateMessagePrice";

        $url = $url."?"."Token=".urlencode(config("constant.SMS.token"));
        $SMSMessageText = urlencode($message);
        $url = $url."&"."SMSMessageText=".$SMSMessageText ;

        $url = $url."&"."LineNumber=public";
        $url = $url."&"."Mobiles=".$phones;

        $json = file_get_contents($url);
        $result = json_decode($json);
        return $result;
//echo $result->Status;

    }
}
