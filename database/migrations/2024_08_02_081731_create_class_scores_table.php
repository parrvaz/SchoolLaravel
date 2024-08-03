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
        Schema::create('classScores', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->foreignId("course_id")->constrained();
            $table->integer("expected")->default(0);
            $table->integer("totalScore")->default(0);
            $table->timestamps();
        });

        Schema::create('classScore_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId("classScore_id")->constrained();
            $table->foreignId("content_id")->constrained();
            $table->timestamps();
        });

        Schema::create('student_classScore', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained();
            $table->foreignId("classScore_id")->constrained();
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
        Schema::dropIfExists('student_classScore');
        Schema::dropIfExists('classScore_contents');
        Schema::dropIfExists('classScores');
    }
};
