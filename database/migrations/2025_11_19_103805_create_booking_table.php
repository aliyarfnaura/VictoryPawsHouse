<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');

            $table->foreignId('id_pengguna')
                ->references('id_pengguna')->on('pengguna')
                ->onDelete('cascade');

            $table->foreignId('id_layanan')
                ->nullable()
                ->references('id_layanan')->on('layanan')
                ->onDelete('set null'); 

            $table->string('nama', 100);
            $table->string('nama_hewan', 100);
            $table->string('nomor_hp', 20);
            $table->string('jenis_hewan', 100);
            $table->enum('gender_hewan', ['Jantan', 'Betina']);
            $table->dateTime('jadwal');
            $table->string('durasi', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->enum('status', ['pending', 'dibayar', 'selesai', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};