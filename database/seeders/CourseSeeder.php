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
            ['id' => 1, 'name' => 'فارسی ۱', 'title' => 'فارسی', 'grade_id' => 10],
            ['id' => 2, 'name' => 'نگارش ۱', 'title' => 'نگارش', 'grade_id' => 10],
            ['id' => 3, 'name' => 'دین و زندگی ۱', 'title' => 'دینی', 'grade_id' => 10],
            ['id' => 4, 'name' => 'عربی ۱', 'title' => 'عربی', 'grade_id' => 10],
            ['id' => 5, 'name' => 'شیمی ۱', 'title' => 'شیمی', 'grade_id' => 10],
            ['id' => 6, 'name' => 'ریاضی ۱', 'title' => 'ریاضی', 'grade_id' => 10],
            ['id' => 7, 'name' => 'فیزیک ۱', 'title' => 'فیزیک', 'grade_id' => 10],
            ['id' => 8, 'name' => 'آمادگی دفاعی', 'title' => 'دفاعی', 'grade_id' => 10],
            ['id' => 9, 'name' => 'زیست شناسی ۱', 'title' => 'زیست', 'grade_id' => 10],
            ['id' => 10, 'name' => 'آزمایشگاه علوم تجربی ۱', 'title' => 'آزمایشگاه', 'grade_id' => 10],
            ['id' => 11, 'name' => 'جغرافیا', 'title' => 'جغرافیا', 'grade_id' => 10],
            ['id' => 12, 'name' => 'هنر', 'title' => 'هنر', 'grade_id' => 10],
            ['id' => 13, 'name' => 'کارگاه کارآفرینی', 'title' => 'کارآفرینی', 'grade_id' => 10],
            ['id' => 14, 'name' => 'تفکر و سواد رسانه ای', 'title' => 'تفکر', 'grade_id' => 10],
            ['id' => 15, 'name' => 'انگلیسی ۱', 'title' => 'انگلیسی', 'grade_id' => 10],
            ['id' => 16, 'name' => 'هندسه ۱', 'title' => 'هندسه', 'grade_id' => 10],

            ['id' => 17, 'name' => 'هنر', 'title' => 'هنر', 'grade_id' => 11],
            ['id' => 18, 'name' => 'فارسی ۲', 'title' => 'فارسی', 'grade_id' => 11],
            ['id' => 19, 'name' => 'نگارش ۲', 'title' => 'نگارش', 'grade_id' => 11],
            ['id' => 20, 'name' => 'دین و زندگی ۲', 'title' => 'دین و زندگی', 'grade_id' => 11],
            ['id' => 21, 'name' => 'عربی ۲', 'title' => 'عربی', 'grade_id' => 11],
            ['id' => 22, 'name' => 'شیمی ۲', 'title' => 'شیمی', 'grade_id' => 11],
            ['id' => 23, 'name' => 'ریاضی ۲', 'title' => 'ریاضی', 'grade_id' => 11],
            ['id' => 24, 'name' => 'زیست شناسی ۲', 'title' => 'زیست', 'grade_id' => 11],
            ['id' => 25, 'name' => 'آزمایشگاه علوم تجربی ۲', 'title' => 'آزمایشگاه', 'grade_id' => 11],
            ['id' => 26, 'name' => 'تاریخ معاصر ایران', 'title' => 'تاریخ', 'grade_id' => 11],
            ['id' => 27, 'name' => 'فیزیک ۲', 'title' => 'فیزیک', 'grade_id' => 11],
            ['id' => 28, 'name' => 'انگلیسی ۲', 'title' => 'انگلیسی', 'grade_id' => 11],
            ['id' => 29, 'name' => ' زمین شناسی', 'title' => 'زمین', 'grade_id' => 11],
            ['id' => 30, 'name' => ' انسان و محیط زیست', 'title' => 'محیط', 'grade_id' => 11],
            ['id' => 31, 'name' => 'تربیت بدنی ۲', 'title' => 'ورزش', 'grade_id' => 11],
            ['id' => 32, 'name' => 'حسابان ۱', 'title' => 'حسابان', 'grade_id' => 11],
            ['id' => 33, 'name' => 'آمار و احتمال', 'title' => 'آمار', 'grade_id' => 11],
            ['id' => 34, 'name' => 'هندسه ۲', 'title' => 'هندسه', 'grade_id' => 11],
            ['id' => 35, 'name' => 'علوم و فنون ادبی ۲', 'title' => ' فنون ادبی', 'grade_id' => 11],
            ['id' => 36, 'name' => 'ریاضی و آمار ۲', 'title' => 'ریاضی', 'grade_id' => 11],
            ['id' => 37, 'name' => 'جغرافیا ۲', 'title' => 'جغرافیا', 'grade_id' => 11],
            ['id' => 38, 'name' => 'تاریخ ۲', 'title' => 'تاریخ', 'grade_id' => 11],
            ['id' => 39, 'name' => 'جامعه شناسی ۲', 'title' => 'جامعه شناسی', 'grade_id' => 11],
            ['id' => 40, 'name' => 'روان شناسی', 'title' => 'روان شناسی', 'grade_id' => 11],
            ['id' => 41, 'name' => ' فلسفه ۱', 'title' => 'فلسفه', 'grade_id' => 11],
            ['id' => 42, 'name' => 'فیزیک ۲', 'title' => 'فیزیک', 'grade_id' => 11],
            ['id' => 43, 'name' => 'شیمی ۲', 'title' => 'شیمی', 'grade_id' => 11],

            ['id' => 44, 'name' => 'فارسی ۳', 'title' => 'فارسی', 'grade_id' => 12],
            ['id' => 45, 'name' => 'نگارش ۳', 'title' => 'نگارش', 'grade_id' => 12],
            ['id' => 46, 'name' => 'دین و زندگی ۳', 'title' => 'دینی', 'grade_id' => 12],
            ['id' => 47, 'name' => 'عربی ۳', 'title' => 'عربی', 'grade_id' => 12],
            ['id' => 48, 'name' => 'شیمی ۳', 'title' => 'شیمی', 'grade_id' => 12],
            ['id' => 49, 'name' => 'ریاضی گسسته', 'title' => 'گسسته', 'grade_id' => 12],
            ['id' => 50, 'name' => 'فیزیک ۳', 'title' => 'فیزیک', 'grade_id' => 12],
            ['id' => 51, 'name' => 'مدیریت خانواده', 'title' => 'مدیریت', 'grade_id' => 12],
            ['id' => 52, 'name' => 'حسابان ۲', 'title' => 'حسابان', 'grade_id' => 12],
            ['id' => 53, 'name' => 'انگلیسی ۳', 'title' => 'انگلیسی', 'grade_id' => 12],
            ['id' => 54, 'name' => 'ریاضی ۳', 'title' => 'ریاضی', 'grade_id' => 12],
            ['id' => 55, 'name' => 'زیست شناسی ۳', 'title' => 'زیست', 'grade_id' => 12],
            ['id' => 56, 'name' => 'فیزیک ۳', 'title' => 'فیزیک', 'grade_id' => 12],
            ['id' => 57, 'name' => 'تربیت بدنی ۱', 'title' => 'ورزش', 'grade_id' => 10],
            ['id' => 58, 'name' => 'تربیت بدنی ۳', 'title' => 'ورزش', 'grade_id' => 12],

        ]);
    }
}


namespace Database\Seeders;
