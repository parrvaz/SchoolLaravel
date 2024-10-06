<?php

namespace App\Traits;

use App\Models\ModelHasRole;
use App\Models\UserGrade;
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

    public function getAllPermissionsName($user_id,$business_id){
        $roleRow= ModelHasRole::where('business_id',$business_id)->where('model_id',$user_id)->first();
//        return $roleRow!= null ? $roleRow->role->permissions->pluck('name')->toArray()
//            : ModelHasPermission::where('business_id',$business_id)->where('model_id',$user_id)->get()->map(function ($item){
//                return $item->permission->name;
//            })->toArray();
    }
}
