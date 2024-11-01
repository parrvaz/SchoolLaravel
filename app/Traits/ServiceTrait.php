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

    public static function gToJ($date){
        if ($date == null)
            return null;
        try {
            return Jalalian::forge($date)->format("Y/m/d");
        }catch (\Exception $e){
            return str_replace('-', '/', $date);
        }
    }

    public static function gToJDash($date){
        try {
            return Jalalian::forge($date)->format("Y-m-d");
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


    public function saveGroupFile($request,$prePath,$name){
        $paths = [];
        if ($request->hasFile($name)){
            foreach ($request->file($name) as $item){
                $paths[]= $this->saveFile($item,$prePath,$name);
            }
        }

        return $paths;
    }

    public function saveSingleFile($request,$prePath,$name="photo"){// ذخیره تصویر در صورت آپلود
        $photoPath = null;
        if ($request->hasFile($name)) {
            $file = $request->file($name);
            $photoPath= $this->saveFile($file,$prePath,$name);
        }
        return $photoPath;
    }

    private function saveFile($file,$prePath,$name="photo"){// ذخیره تصویر در صورت آپلود
        $timestamp = now()->timestamp; // دریافت timestamp
        $extension = $file->getClientOriginalExtension(); // گرفتن پسوند فایل
        $oldName = $file->getClientOriginalName(); // گرفتن پسوند فایل
        $filename = $oldName."_". $timestamp . '.' . $extension; // ایجاد نام یونیک با timestamp
        $photoPath = $file->storeAs($prePath,$filename, 'public');
        return $photoPath;
    }

}
