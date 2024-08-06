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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_grade_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->date("date");
            $table->string("title")->nullable();
            $table->timestamps();
        });

        Schema::create('test_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("test_id")->constrained();
            $table->foreignId("course_id")->constrained();
            $table->integer("average")->default(0);
            $table->integer("expected")->default(0);
            $table->timestamps();
        });

        Schema::create('test_course_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId("test_course_id")->constrained();
            $table->foreignId("content_id")->constrained();
            $table->timestamps();
        });

        Schema::create('student_test_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained();
            $table->foreignId("test_course_id")->constrained();
            $table->integer("score")->default(0);
            $table->integer("balance")->default(0);
            $table->string("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_test_course');
        Schema::dropIfExists('test_course_contents');
        Schema::dropIfExists('test_courses');
        Schema::dropIfExists('tests');
    }
};
