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
        Schema::create('study_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("course_id")->constrained()->cascadeOnDelete();
            $table->date("date");
            $table->time("start");
            $table->time("end");
            $table->timestamps();
        });

        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("course_id")->constrained()->cascadeOnDelete();
            $table->date("date");
            $table->time("start");
            $table->time("end");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studies');
        Schema::dropIfExists('study_plans');
    }
};
