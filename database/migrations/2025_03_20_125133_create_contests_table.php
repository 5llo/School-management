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
        Schema::create('contests', function (Blueprint $table) {
            $table->id(); // Unique ID

            $table->string('name');

            $table->text('description')->nullable();

            $table->dateTime('date_start');

            $table->integer('duration');
            $table->timestamps();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contests');
    }
};
