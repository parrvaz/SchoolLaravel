<?php

namespace Database\Seeders;

use App\Traits\ServiceTrait;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
class StudentSeeder extends Seeder
{

    use ServiceTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstName = ["محمد","رضا","علی","سینا","اشکان","مصطفی","سهیل","صادق","حسین","نیما","مرتضی","نوید","هادی","مهدی","آرمین"];
        $lastName = ["جعفری","مسلمی","اسدی","معماری","فاطمی","عرب","قربانی","کریمی","کیانی","بیگی","عزتی","صفایی","رادخوش","عنایتی","جباری","محمدی","کاظمی"];

        $students = [];
        for ($j=22;$j<25;$j++){
            for ($i=0;$i<30;$i++){
                $students [] = [
                    'firstName'=> $firstName[array_rand($firstName)] ,
                    'lastName'=> $lastName[array_rand($lastName)],
                    'nationalId'=> str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT),
                    'classroom_id'=>$j,
                    'birthday'=>fake()->dateTimeBetween('-16 years', '-15 years')->format('Y/m/d'),
                    'onlyChild'=>fake()->boolean,
                    'address'=>null,
                    'phone'=> "09".str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT) ,
                    'fatherPhone'=> "09".str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT),
                    'motherPhone'=> "09".str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT),
                    'socialMediaID'=>null,
                    'numberOfGlasses'=>null,
                    'leftHand'=>fake()->boolean,
                    'religion'=>"اسلام",
                    'specialDisease'=>null,
                    'picture'=>null
                ];
            }
        }

        DB::table('students')->insert($students);



    }
}
