<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
            'name' => "admin",
            'guard_name' => "api",
        ],
        [
            'name' => "manager",
            'guard_name' => "api",
        ],
            ['name' => "teacher",
                'guard_name' => "api",
            ],
            ['name' => "student",
                'guard_name' => "api",
            ],
            ['name' => "parent",
                'guard_name' => "api",
            ],
        ]);
    }
}
