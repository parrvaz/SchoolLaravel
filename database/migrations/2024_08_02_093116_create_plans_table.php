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
            $table->foreignId('user_grade_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained();
            $table->date("date");
            $table->foreignId("course_id")->constrained();
            $table->integer("minutes");
            $table->timestamps();
        });

        Schema::create('student_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained();
            $table->date("date");
            $table->foreignId("course_id")->constrained();
            $table->integer("minutes");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_weeklyPlan');
        Schema::dropIfExists('weekly_plans');
    }
};
