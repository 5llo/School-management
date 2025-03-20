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
        Schema::create('schools_classes_division_posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();

            // ✅ تضيف العمود أولاً!
            $table->unsignedBigInteger('school_classes_division_id');

            $table->foreign('school_classes_division_id', 'scd_division_post_fk')
                  ->references('id')
                  ->on('schools_classes_division') // أو schools_classes_divisions لو اسم الجدول كذا!
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools_classes_division_posts');
    }
};
