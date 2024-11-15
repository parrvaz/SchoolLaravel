<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    public function sendMessage($message,$phones){
//        $message= " اس ام سا تبلیعانی";
//        $phone= "09383851960";
        $url = "https://portal.amootsms.com/rest/SendSimple";

        $url = $url."?"."Token=".urlencode(config("constant.SMS.token"));
        $nowIran = new DateTime('now', new DateTimeZone('IRAN'));
        $url = $url."&"."SendDateTime=".urlencode($nowIran->format('c'));

        $url = $url."&"."SMSMessageText=".urlencode($message);
        $url = $url."&"."LineNumber=public";

        $url = $url."&"."Mobiles=".$phones;

        $json = file_get_contents($url);
        echo $json;

//$result = json_decode($json);
    }
}
