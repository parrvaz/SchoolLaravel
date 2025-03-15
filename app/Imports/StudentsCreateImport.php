<?php

namespace App\Imports;

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Traits\ServiceTrait;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Morilog\Jalali\Jalalian;

class StudentsCreateImport implements OnEachRow, WithStartRow
{
    use ServiceTrait;
    protected $datas;
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
        $phone =  Str::length($row[4]) == 10 ? "0" . $row[4] : $row[4];
        $fatherPhone =  Str::length($row[5]) == 10 ? "0" . $row[5] : $row[5];
        $classNum = Classroom::where("school_grade_id", $this->request->schoolGrade->id)
            ->where("number", $row[3])->first()->id ?? null;



            $row[2] =  Str::length($row[2]) == 9 ? "0" . $row[2] : (Str::length($row[2]) == 8 ? "00" . $row[2] : $row[2]);

            // ذخیره دانش‌آموز در دیتابیس
            $student = Student::create([
                'firstName'    => $row[0],
                'lastName'     => $row[1],
                'nationalId'   => $row[2],
                'classroom_id' => $classNum,
                'phone'        => $phone,
                'fatherPhone'  => $fatherPhone,
                'birthday'     => $row[7] != null ? $this->formatDate($row[7]) : null,
                'address'      => $row[8] ?? null,
            ]);

            // ایجاد کاربر و ثبت نقش‌ها
            $user = User::create([
                "name"     => $student->firstName . " " . $student->lastName,
                "phone"    => $student->phone,
                "password" => bcrypt($student->nationalId),
            ]);
            $user->assignRole('student');
            $user->modelHasRole()->update(["idInRole" => $student->id]);

            $parentUser = User::create([
                "name"     => "ولی " . $student->firstName . " " . $student->lastName,
                "phone"    => $student->fatherPhone,
                "password" => bcrypt($student->nationalId),
            ]);
            $parentUser->assignRole('parent');
            $parentUser->modelHasRole()->update(["idInRole" => $student->id]);

    }

    public function startRow(): int
    {
        return 2;
    }

    public function formatDate($birthday)
    {
        $date=null;
        // بررسی اگر فرمت به صورت yyyyMMdd است
        if (Str::length($birthday) === 8 && is_numeric($birthday)) {
            $year = substr($birthday, 0, 4);
            $month = substr($birthday, 4, 2);
            $day = substr($birthday, 6, 2);
            // تبدیل به فرمت Y/m/d
            $date = Jalalian::fromFormat('Y/m/d', "$year/$month/$day")->format('Y/m/d');
        }
        // بررسی اگر فرمت با '/' جدا شده است
        elseif (strpos($birthday, '/') !== false) {
            // فرض کنیم فرمت ورودی به صورت Y/m/d است
            $date = Jalalian::fromFormat('Y/m/d', $birthday)->format('Y/m/d');
        }
        // اگر هیچکدام نبود، یک مقدار null یا خطا برگردانیم
        return self::jToG($date);
    }
}
