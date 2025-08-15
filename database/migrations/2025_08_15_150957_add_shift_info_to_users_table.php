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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('shift_type', ['day', 'night'])->default('day')->after('role');
            $table->time('start_time')->default('08:00:00')->after('shift_type');
            $table->time('end_time')->default('17:00:00')->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['shift_type', 'start_time', 'end_time']);
        });
    }
};
