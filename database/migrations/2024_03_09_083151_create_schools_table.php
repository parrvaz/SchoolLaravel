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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->string("title");
            $table->bigInteger("wallet")->default(2000000);
            $table->string('logo')->nullable();
            $table->string("location")->nullable();
            $table->string("phone")->nullable();
            $table->boolean("gender")->nullable();
            $table->string("postalCode")->nullable();
            $table->string("bankAccount")->nullable();
            $table->string("website")->nullable();
            $table->string("socialMedia")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
