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
        Schema::table('parents', function (Blueprint $table) {
        $table->string('fcmtoken')->nullable();
    });

    Schema::table('teachers', function (Blueprint $table) {
        $table->string('fcmtoken')->nullable();
    });

    Schema::table('bus_drivers', function (Blueprint $table) {
        $table->string('fcmtoken')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
        $table->dropColumn('fcmtoken');
    });

    Schema::table('teachers', function (Blueprint $table) {
        $table->dropColumn('fcmtoken');
    });

    Schema::table('bus_drivers', function (Blueprint $table) {
        $table->dropColumn('fcmtoken');
    });
    }
};
