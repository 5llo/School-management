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
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
    $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();

    // Polymorphic fields
    $table->unsignedBigInteger('user_id');
    $table->string('user_type');

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_participants');
    }
};
