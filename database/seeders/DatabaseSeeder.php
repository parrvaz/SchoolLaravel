<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
           FieldSeeder::class,
           GradeSeeder::class,
           RoleSeeder::class,
           CourseSeeder::class,
           ContentSeeder::class,
        ]);
    }
}
