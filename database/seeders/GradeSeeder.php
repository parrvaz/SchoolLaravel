<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grades')->insert([[
            'id' => 10,
            'number' => 10,
        ],
        [
            'id' => 11,
            'number' => 11,
        ],
        [
                'id' => 12,
                'number' => 12,
        ]
        ]);
    }
}
