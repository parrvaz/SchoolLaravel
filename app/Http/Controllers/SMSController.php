<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

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

    // ✅ 2. تأیید کد OTP و ثبت نام یا ورود
    public function verifyOtp(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:11',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // پیدا کردن کاربر بر اساس شماره تلفن و کد OTP
        $user = User::where('phone', $request->phone)->where('otp', $request->otp)->first();

        if (!$user) {
            return response()->json(['error' => 'کد وارد شده نادرست است.'], 401);
        }

        // پاک کردن OTP بعد از تأیید موفق
        $user->update(['otp' => null]);

        // 📌 ورود و ایجاد توکن
        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'ورود موفقیت‌آمیز بود', 'token' => $token], 200);
    }

    // ✅ 3. متد ارسال پیامک (بسته به سرویس پیامکی خود تغییر دهید)
    private function sendSms($phone, $message)
    {
        Http::post('https://smsapi.example.com/send', [
            'to' => $phone,
            'message' => $message,
            'api_key' => 'YOUR_API_KEY',
        ]);
    }
}
