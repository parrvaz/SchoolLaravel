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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('nationalId')->nullable();
            $table->string("phone")->nullable();
            $table->string('degree')->nullable();
            $table->string('personalId')->nullable();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->boolean('isAssistant')->default(false);
            $table->timestamps();
        });

        Schema::create('class_course_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_course_teacher');
        Schema::dropIfExists('teachers');
    }
};
