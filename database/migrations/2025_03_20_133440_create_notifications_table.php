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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Unique ID for the notification

            $table->morphs('notifiable'); // Creates notifiable_id and notifiable_type columns

            $table->string('type')->nullable(); // Type of notification (e.g., 'App\Notifications\NewMessage')

            $table->text('data'); // JSON data for the notification

            $table->boolean('read')->default(false); // Whether the notification has been read

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
