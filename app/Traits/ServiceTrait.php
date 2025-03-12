<?php

namespace App\Traits;

use App\AccountingItem;
use App\Models\ModelHasRole;
use App\Models\SchoolGrade;
use App\OlPrice;
use App\Repositories\AccountingItemRepository;
use App\Services\AccountService;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;
use niklasravnsborg\LaravelPdf\PdfWrapper;
use function PHPUnit\Framework\isNull;

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
        $filePath = null;
        if ($request->hasFile($name)) {
            $file = $request->file($name);
            $filePath= $this->saveFile($file,$prePath,$name);
        }
        return $filePath;
    }

    public function deleteGroupFile($items,$prePath=""){
        foreach ($items as $item){
            $this->deleteFile($item,$prePath);
        }
    }

    public function deleteFile($name,$prePath=""){
        $filename = $prePath . $name;
        if (Storage::disk('public')->exists($filename)) {
            Storage::disk('public')->delete($filename);
        }
    }

    private function saveFile($file,$prePath,$name="photo"){// ذخیره تصویر در صورت آپلود
        $timestamp = now()->timestamp; // دریافت timestamp
        $extension = $file->getClientOriginalExtension(); // گرفتن پسوند فایل
        $oldName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $oldName."_". $timestamp . '.' . $extension; // ایجاد نام یونیک با timestamp
        $photoPath = $file->storeAs($prePath,$filename, 'public');
        return $photoPath;
    }


    public function scoreFeedback($score,$total,$expected){
        if ($score === null)
            return null;
        $feedback = null;

        if  ($score == $total)
            $feedback = "😎";
        elseif ( $score >$total-(($total-$expected)/2))
            $feedback = "👌🏻";
        elseif ( $score >$expected)
            $feedback = "👍🏻";
        elseif ( $score >$expected/2)
            $feedback = "😐";
        elseif ( $score >$expected/4)
            $feedback = "🫢";
        elseif($score >= 0)
            $feedback = "🤬";

        return $feedback;
    }

    public function zeroChar($number){
        return $number == 0 ? '0' : $number;
    }


    public function pdfStuff($viewName, $header, $items, $name = null, $exception = null, $orientation = 'P')
    {
        //delete old items in report directory
        $file = new Filesystem;
        $file->cleanDirectory(public_path('reports'));

        $pdf = new PdfWrapper();
        $time = Carbon::now()->timestamp;
        $filePath = "reports/";
        $pdf->loadView($viewName, compact(['items', 'header', 'exception']), [], [
            'mode' => 'utf-8',
            'orientation' => $orientation,
        ])->save($filePath . ($name ?? $viewName) . "-{$time}." . 'pdf');

        return response()->download(public_path("reports/" . ($name ?? $viewName) . "-{$time}.pdf"));

    }


}
