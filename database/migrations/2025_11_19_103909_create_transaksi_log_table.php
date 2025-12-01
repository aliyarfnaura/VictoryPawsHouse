<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('transaksi_log', function (Blueprint $table) {
        $table->id('id_transaksi');       
        $table->foreignId('id_booking')
              ->references('id_booking')->on('booking')
              ->onDelete('cascade');
        $table->foreignId('id_pengguna')
              ->references('id_pengguna')->on('pengguna')
              ->onDelete('cascade');
        $table->dateTime('tanggal_transaksi')->useCurrent();
        $table->decimal('total_harga', 10, 2)->nullable();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('transaksi_log');
    }
};
