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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('nationalId')->nullable();
            $table->foreignId('classroom_id')->constrained()->nullable();
            $table->date("birthday")->nullable();
            $table->boolean("onlyChild")->nullable();
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->string("socialMediaID")->nullable();
            $table->smallInteger("numberOfGlasses")->nullable();
            $table->boolean("leftHand")->nullable();
            $table->string("religion")->nullable();
            $table->string("specialDisease")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
