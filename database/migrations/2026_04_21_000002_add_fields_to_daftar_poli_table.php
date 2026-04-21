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
        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->enum('status', ['pending', 'proses', 'selesai', 'dibatalkan'])->default('pending')->after('no_antrian');
            $table->integer('no_antrian_dipanggil')->nullable()->after('status');
            $table->enum('payment_status', ['unpaid', 'pending_verification', 'verified'])->default('unpaid')->after('no_antrian_dipanggil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->dropColumn(['status', 'no_antrian_dipanggil', 'payment_status']);
        });
    }
};
