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
        DB::table('contents')->delete();
        

        DB::table('menu_items')->insert([
            [
                'label' => "dashboard",
                'hasSub' => false,
            ],
            [
                'label' => "examList",
                'hasSub' => false,
            ],
            [
                'label' => "examStore",
                'hasSub' => false,
            ],
            [
                'label' => "classroom",
                'hasSub' => false,
            ],
            [
                'label' =>  "student",
                'hasSub' => false,
            ],
            [
                'label' =>  "teacher",
                'hasSub' => false,
            ],
            [
                'label' =>  "assistant",
                'hasSub' => false,
            ],
            [
                'label' => "course",
                'hasSub' => true,
            ],
            [
                'label' =>"courseAssign",
                'hasSub' => false,
//                'parent_id'=>8
            ],
            [
                'label' =>  "courseList",
                'hasSub' => false,
//                'parent_id'=>8
            ],

            [
                'label' => "reports",
                'hasSub' => true,
            ],

        ]);
    }
}
