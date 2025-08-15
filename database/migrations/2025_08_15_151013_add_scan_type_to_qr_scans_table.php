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
        Schema::table('qr_scans', function (Blueprint $table) {
            $table->enum('scan_type', ['check_in', 'check_out'])->default('check_in')->after('scanned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_scans', function (Blueprint $table) {
            $table->dropColumn('scan_type');
        });
    }
};
