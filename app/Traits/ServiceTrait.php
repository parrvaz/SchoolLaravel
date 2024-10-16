<?php

namespace App\Traits;

use App\AccountingItem;
use App\Models\ModelHasRole;
use App\Models\UserGrade;
use App\Repositories\AccountingItemRepository;
use App\Services\AccountService;
use Illuminate\Support\Facades\Lang;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

trait ServiceTrait
{
    public  function findUserGrade($code){
       return UserGrade::whereCode($code)->first();
    }

    public static function gToJ($date){
        try {
            return Jalalian::forge($date)->format("Y/m/d");
        }catch (\Exception $e){
            return str_replace('-', '/', $date);
        }
    }

    public static function jToG($date)
    {

        if (strpos($date, '/') != false)
            return CalendarUtils::createDatetimeFromFormat('Y/m/d', $date)->format('Y/m/d');
        elseif (strpos($date, '-') != false)
            return CalendarUtils::createDatetimeFromFormat('Y-m-d', $date)->format('Y/m/d');
        else return $date;

    }


}
