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
        Schema::create('course_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId("field_id")->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId("course_id")->constrained()->cascadeOnDelete();
            $table->tinyInteger("factor")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_fields');
    }
};
