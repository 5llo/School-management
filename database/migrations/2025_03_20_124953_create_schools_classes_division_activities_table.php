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
        Schema::create('schools_classes_division_activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();

            $table->unsignedBigInteger('school_classes_division_id');

            $table->foreign('school_classes_division_id', 'scd_activities_division_id_fk')
                  ->references('id')
                  ->on('schools_classes_division')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools_classes_division_activities');
    }
};
