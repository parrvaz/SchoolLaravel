<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\StudentExam;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentExamFactory extends Factory
{
    protected $model = StudentExam::class;

    public function definition()
    {
        return [
            'exam_id' =>$this->faker->numberBetween(21,25),
            'score' => $this->faker->numberBetween(0,20),
            'student_id' =>$this->faker->numberBetween(21,50),

        ];
    }
}
