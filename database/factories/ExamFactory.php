<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition()
    {
        return [
            'course_id' => $this->faker->numberBetween(1,16),
            'classroom_id' =>$this->faker->numberBetween(1,3),
            'date' =>$this->faker->date,
            'user_grade_id'=>1,
            'expected'=>$this->faker->numberBetween(10,15),
            'totalScore'=>20,
        ];
    }
}
