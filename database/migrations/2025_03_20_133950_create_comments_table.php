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
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Unique ID for the comment

            $table->morphs('commentable'); // Creates commentable_id and commentable_type columns

            $table->unsignedBigInteger('user_id')->nullable(); // Optional: to associate the comment with a user

            $table->text('body'); // The content of the comment

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
