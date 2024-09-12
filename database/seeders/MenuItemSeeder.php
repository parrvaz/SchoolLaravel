<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu_items')->insert([
            [
                'label' => "داشبورد",
                'hasSub' => false,
            ],
            [
                'label' => "لیست امتحانات",
                'hasSub' => false,
            ],
            [
                'label' => "ثبت امتحان",
                'hasSub' => false,
            ],
            [
                'label' => "ثبت آزمون تستی",
                'hasSub' => false,
            ],
            [
                'label' => "لیست کلاس ها",
                'hasSub' => false,
            ],
            [
                'label' =>  "لیست دانش آموزان",
                'hasSub' => false,
            ],
            [
                'label' =>  "لیست معلمان",
                'hasSub' => false,
            ],
            [
                'label' => "درس ها",
                'hasSub' => true,
            ],
            [
                'label' =>"تخصیص درس",
                'hasSub' => false,
                'parent_id'=>8
            ],
            [
                'label' =>  "لیست درس ها",
                'hasSub' => false,
                'parent_id'=>8
            ],

            [
                'label' => "گزارشات",
                'hasSub' => true,
            ],
            [
                'label' =>  "فراوانی امتحانات",
                'hasSub' => false,
                'parent_id'=>11
            ],
            [
                'label' => "روند امتحانات کتبی",
                'hasSub' => false,
                'parent_id'=>11

            ],
            [
                'label' => "روند امتحانات شفاهی",
                'hasSub' => false,
                'parent_id'=>11
            ]
        ]);
    }
}
