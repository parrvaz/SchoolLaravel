<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Http\Request;

class StudentsImport implements ToModel
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       $row = array_filter($row, function($value) {
            return !is_null($value);
        });

        return new Student([
            'firstName'    => $row[0],  // به جای 'firstName' از اندیس عددی استفاده کنید
            'lastName'     => $row[1],
            'nationalId'   => $row[2],
            'classroom_id' => Classroom::where("user_grade_id", $this->request->userGrade->id)->where("number",$row[3])->first()->id ?? null,
            'phone'        => $row[4],
            'fatherPhone'  => $row[5],
            'motherPhone'  => $row[6],
            'birthday'     => $row[7],
            'address'      => $row[8],
        ]);

    }

    public function startRow(): int
    {
        return 2;
    }
}
