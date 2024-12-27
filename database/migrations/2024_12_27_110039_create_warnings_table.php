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
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->timestamps();
        });


        Schema::create('classroom_homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId("classroom_id")->constrained()->cascadeOnDelete();
            $table->foreignId("homework_id")->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warnings');
    }
};
