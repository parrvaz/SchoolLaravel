<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            ['id' => 1, 'name' => 'فارسی ۱', 'title' => 'فارسی', 'grade_id' => 10, 'factor' => 2, 'type' => 0],
            ['id' => 2, 'name' => 'نگارش ۱', 'title' => 'نگارش', 'grade_id' => 10, 'factor' => 2, 'type' => 0],
            ['id' => 3, 'name' => 'دین و زندگی ۱', 'title' => 'دینی', 'grade_id' => 10, 'factor' => 2, 'type' => 0],
            ['id' => 4, 'name' => 'عربی ۱', 'title' => 'عربی', 'grade_id' => 10, 'factor' => 2, 'type' => 0],
            ['id' => 5, 'name' => 'شیمی ۱', 'title' => 'شیمی', 'grade_id' => 10, 'factor' => 3, 'type' => 1],
            ['id' => 6, 'name' => 'ریاضی ۱', 'title' => 'ریاضی', 'grade_id' => 10, 'factor' => 4, 'type' => 1],
            ['id' => 7, 'name' => 'فیزیک ۱', 'title' => 'فیزیک', 'grade_id' => 10, 'factor' => 4, 'type' => 1],
            ['id' => 8, 'name' => 'آمادگی دفاعی', 'title' => 'دفاعی', 'grade_id' => 10, 'factor' => 3, 'type' => 0],
            ['id' => 9, 'name' => 'زیست شناسی ۱', 'title' => 'زیست', 'grade_id' => 10, 'factor' => 3, 'type' => 1],
            ['id' => 10, 'name' => 'آزمایشگاه علوم تجربی ۱', 'title' => 'آزمایشگاه', 'grade_id' => 10, 'factor' => 2, 'type' => 1],
            ['id' => 11, 'name' => 'جغرافیا', 'title' => 'جغرافیا', 'grade_id' => 10, 'factor' => 2, 'type' => 0],
            ['id' => 12, 'name' => 'هنر', 'title' => 'هنر', 'grade_id' => 10, 'factor' => 2, 'type' => 0],
            ['id' => 13, 'name' => 'کارگاه کارآفرینی', 'title' => 'کارآفرینی', 'grade_id' => 10, 'factor' => 2, 'type' => 0],
            ['id' => 14, 'name' => 'تفکر و سواد رسانه ای', 'title' => 'تفکر', 'grade_id' => 10, 'factor' => 2, 'type' => 0],
            ['id' => 15, 'name' => 'انگلیسی ۱', 'title' => 'انگلیسی', 'grade_id' => 10, 'factor' => 3, 'type' => 0],
            ['id' => 16, 'name' => 'هندسه ۱', 'title' => 'هندسه', 'grade_id' => 10, 'factor' => 2, 'type' => 1],
        ]);
    }
}


namespace Database\Seeders;
