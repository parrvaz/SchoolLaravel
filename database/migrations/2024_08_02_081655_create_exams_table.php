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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_grade_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->date("date");
            $table->foreignId("course_id")->constrained()->cascadeOnDelete();
            $table->integer("expected")->default(0);
            $table->integer("totalScore")->default(0);
            $table->boolean("status")->default(0);
            $table->boolean("isGeneral")->default(0);
            $table->tinyInteger("type")->default(1);//1:katbi 2:shafahi 3:testi
            $table->timestamps();
        });

        Schema::create('content_exam', function (Blueprint $table) {
            $table->id();
            $table->foreignId("exam_id")->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger("content_id");
            $table->timestamps();
        });

        Schema::create('student_exam', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("exam_id")->constrained()->cascadeOnDelete();
            $table->float("score")->default(0);
            $table->float("scaledScore")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exam');
        Schema::dropIfExists('content_exam');
        Schema::dropIfExists('exams');
    }
};
