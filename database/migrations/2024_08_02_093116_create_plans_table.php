<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_grade_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string("title");
            $table->timestamps();
        });

        Schema::create('course_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('day');
            $table->time('start');
            $table->time('end');
            $table->timestamps();
        });

        Schema::create('plan_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_student');
        Schema::dropIfExists('course_plan');
        Schema::dropIfExists('plans');
    }
};
