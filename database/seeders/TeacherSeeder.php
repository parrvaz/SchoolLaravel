<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstName = ["محمد","رضا","علی","کریم","مصطفی","صادق","حسین","مرتضی","مهدی","میلاد"];
        $lastName = ["تدین","مهدوی","جانباز","هادوی","نصیری","شیرازی","جالسیان","نوریان","شکربیگی","جهانی"];

        $teachers= [];
            for ($i=0;$i<20;$i++){
                $teachers [] = [
                    'firstName'=>$firstName[array_rand($firstName)],
                    'lastName'=>$lastName[array_rand($lastName)],
                    'nationalId'=> str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT),
                    'degree'=> "لیسانس" ,
                    'personalId'=> str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                    'school_id'=>8,
                    'phone'=>"09".str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT),
                    'isAssistant'=>false,
                ];
            }


        DB::table('teachers')->insert($teachers);


    }
}
