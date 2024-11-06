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
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->noActionOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date("date")->nullable();
            $table->text("title")->nullable();
            $table->text("description")->nullable();
            $table->float("score")->nullable();
            $table->float("expected")->nullable();
            $table->boolean("isFinal")->default(0);
            $table->string("link")->nullable();
            $table->timestamps();
        });

        Schema::create('classroom_homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId("classroom_id")->constrained()->cascadeOnDelete();
            $table->foreignId("homework_id")->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('file_homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId("homework_id")->constrained()->cascadeOnDelete();
            $table->string("file");
            $table->tinyInteger("type")->nullable();
            $table->string("format")->nullable();
            $table->timestamps();
        });


        Schema::create('student_homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("homework_id")->constrained()->cascadeOnDelete();
            $table->float("score")->default(0);
            $table->float("scaledScore")->default(0);
            $table->string("note")->nullable();
            $table->string("solution")->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_homework');
        Schema::dropIfExists('classroom_homework');
        Schema::dropIfExists('homework');
    }
};
