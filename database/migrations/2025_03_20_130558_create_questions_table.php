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
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Unique ID


            $table->text('question'); // The question text

            $table->string('correct_answer'); // The correct answer

            $table->json('options'); // Store options as JSON

            $table->timestamps();
            $table->foreignId('contest_id')->constrained('contests')->cascadeOnDelete();


                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
