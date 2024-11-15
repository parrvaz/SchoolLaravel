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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // شناسه فرستنده پیام
            $table->string('subject'); // موضوع پیام
            $table->text('body'); // متن پیام
            $table->tinyInteger('type')->default(1);//1:message 2:SMS
            $table->timestamps();
        });

        Schema::create('message_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained(); // شناسه پیام
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // شناسه گیرنده پیام
            $table->boolean('isRead')->default(false); // وضعیت خوانده شدن پیام
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_recipients');
        Schema::dropIfExists('messages');
    }
};
