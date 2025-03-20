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

            // الربط مع المحادثة الرئيسية
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();

            // sender polymorphic fields
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // Student, Teacher, Parent, etc...

            // محتوى الرسالة
            $table->text('message')->nullable();

            // مرفقات الملفات (اختياري)
            $table->string('file_url')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
