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
        Schema::table('booking', function (Blueprint $table) {
            // Menambah kolom tanggal checkout untuk logika Hotel
            $table->date('tanggal_checkout')->nullable()->after('jadwal');

            // (Opsional) Kita bikin id_layanan jadi boleh kosong (nullable) 
            // karena sekarang datanya pindah ke tabel detail
            $table->foreignId('id_layanan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            //
        });
    }
};