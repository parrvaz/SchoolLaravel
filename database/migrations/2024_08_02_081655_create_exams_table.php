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
            $table->foreignId('user_grade_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->date("date");
            $table->foreignId("course_id")->constrained();
            $table->integer("expected")->default(0);
            $table->integer("totalScore")->default(0);
            $table->timestamps();
        });

        Schema::create('content_exam', function (Blueprint $table) {
            $table->id();
            $table->foreignId("exam_id")->constrained()->onDelete("cascade");
            $table->foreignId("content_id")->constrained()->onDelete("cascade");
            $table->timestamps();
        });

        Schema::create('student_exam', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->onDelete("cascade");
            $table->foreignId("exam_id")->constrained()->onDelete("cascade");
            $table->integer("score")->default(0);
            $table->string("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exam');
        Schema::dropIfExists('exam_contents');
        Schema::dropIfExists('exams');
    }
};
