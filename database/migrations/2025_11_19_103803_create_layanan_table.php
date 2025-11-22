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
    Schema::create('layanan', function (Blueprint $table) {
        $table->id('id_layanan');

        // === TAMBAHAN RELASI KE PENGGUNA ===
        $table->foreignId('id_pengguna')
              ->references('id_pengguna')->on('pengguna')
              ->onDelete('cascade'); 
        // onDelete('cascade') artinya jika User admin dihapus, layanannya ikut terhapus.
        // Jika tidak ingin terhapus, ganti dengan onDelete('restrict') atau 'set null'.

        $table->string('nama_layanan');
        $table->decimal('harga', 10, 2);
        $table->string('gambar')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
