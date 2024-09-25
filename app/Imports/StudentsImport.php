<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'firstName'  => $row['نام'], // مطمئن شوید که این فیلدها با سرستون‌های اکسل شما هماهنگ هستند
            'lastName'   => $row['نام خانوادگی'],
            'nationalId' => $row['کد ملی'],
            'classroom_id' =>  Classroom::where("number",$row['شماره کلاس'])->first()->id,
            'phone' => $row['تلفن همراه'],
            'fatherPhone' => $row['تلفن پدر'],
            'motherPhone' => $row['تلفن مادر'] ?? null,
            'birthday'   => \Carbon\Carbon::parse($row['birthday']) ?? null, //میلادی شمیسی todo
            'address' => $row['آدرس'] ?? null,
        ]);
    }
}
