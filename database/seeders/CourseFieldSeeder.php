<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('course_fields')->insert([
            ['course_id' => 1, 'field_id' => null],
            ['course_id' => 2, 'field_id' => null],
            ['course_id' => 3, 'field_id' => null],
            ['course_id' => 4, 'field_id' => null],
            ['course_id' => 5, 'field_id' => 1],
            ['course_id' => 5, 'field_id' => 2],
            ['course_id' => 6, 'field_id' => 1],
            ['course_id' => 6, 'field_id' => 2],
            ['course_id' => 7, 'field_id' => 1],
            ['course_id' => 7, 'field_id' => 2],
            ['course_id' => 8, 'field_id' => null],
            ['course_id' => 9, 'field_id' => 2],
            ['course_id' => 10, 'field_id' => null],
            ['course_id' => 11, 'field_id' => null],
            ['course_id' => 12, 'field_id' => null],
            ['course_id' => 13, 'field_id' => null],
            ['course_id' => 14, 'field_id' => null],
            ['course_id' => 15, 'field_id' => null],
            ['course_id' => 16, 'field_id' => 1],
            ['course_id' => 17, 'field_id' => null],
            ['course_id' => 57, 'field_id' => null],

            ['course_id' => 17, 'field_id' => null],
            ['course_id' => 18, 'field_id' => null],
            ['course_id' => 19, 'field_id' => null],
            ['course_id' => 20, 'field_id' => null],
            ['course_id' => 21, 'field_id' => null],
            ['course_id' => 22, 'field_id' => 1],
            ['course_id' => 23, 'field_id' => 2],
            ['course_id' => 24, 'field_id' => 2],
            ['course_id' => 25, 'field_id' => null],
            ['course_id' => 26, 'field_id' => null],
            ['course_id' => 27, 'field_id' => 1],
            ['course_id' => 28, 'field_id' => null],
            ['course_id' => 29, 'field_id' => 2],
            ['course_id' => 30, 'field_id' => null],
            ['course_id' => 31, 'field_id' => null],
            ['course_id' => 32, 'field_id' => 1],
            ['course_id' => 33, 'field_id' => 1],
            ['course_id' => 34, 'field_id' => 1],
            ['course_id' => 35, 'field_id' => 3],
            ['course_id' => 36, 'field_id' => 3],
            ['course_id' => 37, 'field_id' => 3],
            ['course_id' => 38, 'field_id' => 3],
            ['course_id' => 39, 'field_id' => 3],
            ['course_id' => 40, 'field_id' => 3],
            ['course_id' => 41, 'field_id' => 3],
            ['course_id' => 42, 'field_id' => 2],
            ['course_id' => 43, 'field_id' => 2],

            ['course_id' => 44, 'field_id' => null],
            ['course_id' => 45, 'field_id' => null],
            ['course_id' => 46, 'field_id' => null],
            ['course_id' => 47, 'field_id' => null],
            ['course_id' => 48, 'field_id' => 1],
            ['course_id' => 48, 'field_id' => 2],
            ['course_id' => 49, 'field_id' => 1],
            ['course_id' => 50, 'field_id' => 1],
            ['course_id' => 51, 'field_id' => null],
            ['course_id' => 52, 'field_id' => 1],
            ['course_id' => 53, 'field_id' => null],
            ['course_id' => 54, 'field_id' => 2],
            ['course_id' => 55, 'field_id' => 2],
            ['course_id' => 56, 'field_id' => 2],
            ['course_id' => 57, 'field_id' => null],
            ['course_id' => 58, 'field_id' => null]
        ]);
    }
}
