<?php

namespace App\Imports;

namespace App\Imports;

use App\Models\Classroom;
use App\Models\User;
use App\Traits\ServiceTrait;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class StudentsImport implements OnEachRow, WithStartRow, WithValidation
{
    use ServiceTrait;
    protected $request;
    protected $errors = [];  // آرایه برای جمع‌آوری خطاها

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function onRow(Row $row)
    {
        $index = $row->getIndex();
        $row = $row->toArray();

        // داده‌های مورد نیاز را بررسی کنید
        if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
            $this->errors[$index] = "داده‌های ضروری در ردیف {$index} ناقص هستند";
            return;
        }

        $phone =  Str::length($row[4]) == 10 ? "0" . $row[4] : $row[4];
        $fatherPhone =  Str::length($row[5]) == 10 ? "0" . $row[5] : $row[5];
        // بررسی وجود شماره تلفن در دیتابیس
        if (User::where("phone", $phone)->exists() || User::where("phone", $fatherPhone)->exists()) {
            $this->errors[$index] = "{$row[0]} {$row[1]}: شماره تلفن دانش‌آموز یا پدر قبلاً ثبت شده است";
            return;
        }
        // بررسی برابری شماره تلفن دانش‌آموز و پدر
        if ($phone == $fatherPhone) {
            $this->errors[$index] = "{$row[0]} {$row[1]}: شماره تلفن دانش‌آموز و پدر نمی‌تواند یکسان باشد";
            return;
        }

        if (Str::length($row[2]) <8 ||  Str::length($row[2])>10){
            $this->errors[$index] = "{$row[0]} {$row[1]}: کدملی اشتباه است ";
            return;
        }

        $classNum = Classroom::where("user_grade_id", $this->request->userGrade->id)
            ->where("number", $row[3])->first()->id ?? null;
        if ($classNum==null){
            $this->errors[$index] = "{$row[0]} {$row[1]}: شماره کلاس در لیست کلاس ها موجود نیست ";
            return;
        }

        return;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules($request = null): array
    {
        return [
            '0' => 'nullable|min:1|max:50',
            '1' => "nullable|min:1|max:50",
            '2' => 'nullable',
            '3' => 'nullable',
            '4' => 'nullable',
            '5' => 'nullable',
            '6' => 'nullable',
            '7' => 'nullable',
            '8' => 'nullable|string|min:1|max:100',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'نام',
            '1' => 'نام خانوادگی',
            '2' => 'کد ملی',
            '3' => 'شماره کلاس',
            '4' => 'تلفن همراه دانش‌آموز',
            '5' => 'تلفن پدر',
            '6' => 'تلفن مادر',
            '7' => 'تاریخ تولد',
            '8' => 'آدرس',
        ];
    }


    public function getErrors()
    {
        return $this->errors;
    }


}
