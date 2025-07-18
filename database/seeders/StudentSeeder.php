<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
class StudentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstName = ["محمد","رضا","علی","سینا","اشکان","مصطفی","سهیل","صادق","حسین","نیما","مرتضی","نوید","هادی","مهدی","آرمین"];
        $lastName = ["جعفری","مسلمی","اسدی","معماری","فاطمی","عرب","قربانی","کریمی","کیانی","بیگی","عزتی","صفایی","رادخوش","عنایتی","جباری","محمدی","کاظمی"];



    }
}
