<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Traits\ServiceTrait;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Morilog\Jalali\Jalalian;

class StudentsImport implements OnEachRow,WithStartRow,WithValidation
{
    use ServiceTrait;
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        // داده‌های مورد نیاز را بررسی کنید
        if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
            return; // اگر داده‌های ضروری خالی باشند، ردیف نادیده گرفته می‌شود
        }

        $phone =  Str::length($row[4])== 10 ? "0".$row[4] : $row[4];
        $fatherPhone =  Str::length($row[5])== 10 ? "0".$row[5] : $row[5];
        $motherPhone =  Str::length($row[6])== 10 ? "0".$row[6] : $row[6];

        $data = $row[7] != null ? $this->formatDate($row[7]) : null;

        if (User::where("phone",$phone)->exists()  || User::where("phone",$fatherPhone)->exists())
            throw ValidationException::withMessages([
            'error' => $row[0]." ".$row[1].": "." شماره تلفن دانش آموز یا پدر قبلا انتخاب شده است",
            ]);
        if ($phone == $fatherPhone)
            throw ValidationException::withMessages([
                'error' =>$row[0]." ".$row[1].": ". " شماره تلفن دانش آموز و پدر نمیتواند برابر باشد",
            ]);



        $row[2] =  Str::length($row[2])== 9 ? "0".$row[2] : (Str::length($row[2])== 8 ? "00".$row[2]: $row[2]);

        $student =Student::create([
            'firstName'    => $row[0],  // به جای 'firstName' از اندیس عددی استفاده کنید
            'lastName'     => $row[1],
            'nationalId'   => $row[2],
            'classroom_id' => Classroom::where("user_grade_id", $this->request->userGrade->id)->where("number",$row[3])->first()->id ?? null,
            'phone'        =>$phone,
            'fatherPhone'  => $fatherPhone,
            'motherPhone'  => $motherPhone,
            'birthday'     => $data,
            'address'      => $row[8] ?? null,
        ]);


        //create user
        $user = User::create([
            "name"=> $student->firstName." ".$student->lastName,
            "phone"=>$student->phone,
            "password"=> bcrypt($student->nationalId),
        ]);
        //assign role
        $user->assignRole('student');
        $user->modelHasRole()->update(["idInRole"=>$student->id ]);


        //create parent user
        $user = User::create([
            "name"=> "ولی ". $student->firstName." ".$student->lastName,
            "phone"=>$student->fatherPhone,
            "password"=> bcrypt($student->nationalId),
        ]);
        //assign role
        $user->assignRole('parent');
        $user->modelHasRole()->update(["idInRole"=>$student->id ]);



    }



    public function startRow(): int
    {
        return 2;
    }

    public function rules($request=null): array
    {
        return [
            '0'=> 'nullable|min:2|max:50',
            '1'=>"nullable|min:2|max:50",
            '2'=>'nullable',
            '3'=>'nullable',
            '4'=>'nullable',
            '5'=>'nullable',
            '6'=>'nullable',
            '7'=>'nullable',
            '8'=>'nullable|string|min:2|max:100',

        ];

    }
    public function customValidationAttributes()
    {
        return [
            '0' => 'نام',
            '1' => 'نام خانوادگی',
            '2' => 'کد ملی',
            '3' => 'شماره کلاس',
            '4' => 'تلفن همراه دانش آموز',
            '5' => 'تلفن پدر',
            '6' => 'تلفن مادر',
            '7' => 'تاریخ تولد',
            '8' => 'آدرس',
        ];
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
