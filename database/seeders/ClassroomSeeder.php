<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classrooms')->insert([[
            'title'=>"ریاضی ۱۰۱",
            'number'=>101,
            'floor'=>2,
            'school_grade_id'=>8,
            'field_id'=>1,
        ],
            [
                'title'=>"ریاضی ۱۰۲",
                'number'=>102,
                'floor'=>2,
                'school_grade_id'=>8,
                'field_id'=>1,
            ],
            [
                'title'=>"تجربی ۱۰۳",
                'number'=>103,
                'floor'=>1,
                'school_grade_id'=>8,
                'field_id'=>2,
            ]
        ]);
    }
}
