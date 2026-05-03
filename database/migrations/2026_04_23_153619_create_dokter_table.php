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
            Schema::create('dokter', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('alamat', 255);
            $table->string('no_hp', 15);
            // Relasi ke tabel poli yang sudah Anda buat
            $table->foreignId('id_poli')->constrained('poli')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
