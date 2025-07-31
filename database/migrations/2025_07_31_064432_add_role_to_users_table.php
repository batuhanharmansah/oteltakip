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
            $table->enum('role', ['admin', 'employee'])->default('employee')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('emergency_contact_name')->nullable()->after('phone');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->text('address')->nullable()->after('emergency_contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'emergency_contact_name', 'emergency_contact_phone', 'address']);
        });
    }
};
