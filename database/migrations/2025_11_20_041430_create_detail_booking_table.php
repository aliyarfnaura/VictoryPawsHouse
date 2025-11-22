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
        Schema::create('detail_booking', function (Blueprint $table) {
            $table->id('id_detail');

            // Terhubung ke Booking Induk
            $table->foreignId('id_booking')
                ->constrained('booking', 'id_booking')
                ->onDelete('cascade');

            // Terhubung ke Layanan yang dipilih
            $table->foreignId('id_layanan')
                ->constrained('layanan', 'id_layanan')
                ->onDelete('cascade');

            // Simpan harga saat transaksi (Penting untuk history)
            $table->decimal('harga_saat_ini', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_booking');
    }
};