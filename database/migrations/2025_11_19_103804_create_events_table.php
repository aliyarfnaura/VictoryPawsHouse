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
    Schema::create('events', function (Blueprint $table) {
        $table->id('id_event');

        $table->foreignId('id_admin')
              ->references('id_pengguna')->on('pengguna')
              ->onDelete('cascade'); 

        $table->string('nama_event')->nullable();
        $table->text('deskripsi')->nullable();
        $table->string('lokasi')->nullable();
        $table->date('tanggal')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};