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
        Schema::create('absents', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('bell_id')->constrained();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId("user_id")->constrained();
            $table->timestamps();
        });

        Schema::create('absent_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("absent_id")->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absents');
    }
};
