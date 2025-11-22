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
    Schema::create('booking', function (Blueprint $table) {
        $table->id('id_booking');
        
        // 1. Relasi ke PENGGUNA (Customer)
        $table->foreignId('id_pengguna')
              ->references('id_pengguna')->on('pengguna')
              ->onDelete('cascade');

        // 2. [BARU] Relasi ke LAYANAN (Grooming, Hotel, dll)
        // Menggantikan ENUM 'tipe_layanan'
        $table->foreignId('id_layanan')
              ->nullable() // Boleh null jaga-jaga jika layanan dihapus admin
              ->references('id_layanan')->on('layanan') 
              ->onDelete('set null'); // Jika layanan dihapus, booking tidak hilang tapi ID-nya jadi null

        // 3. Data Booking Lainnya
        $table->string('nama', 100);
        $table->string('nama_hewan', 100);
        $table->string('nomor_hp', 20);
        $table->string('jenis_hewan', 100);
        $table->enum('gender_hewan', ['Jantan', 'Betina']);
        $table->dateTime('jadwal');
        $table->string('durasi', 50)->nullable();
        $table->text('catatan')->nullable();
        
        // 4. [PENTING] Simpan Harga Saat Booking
        // Walaupun sudah ada id_layanan, harga di tabel layanan bisa berubah tahun depan.
        // Jadi, kita WAJIB simpan harga "deal" saat transaksi terjadi di sini.
        $table->decimal('total_harga', 10, 2)->default(0);

        $table->enum('status', ['pending', 'dibayar', 'selesai', 'ditolak'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
