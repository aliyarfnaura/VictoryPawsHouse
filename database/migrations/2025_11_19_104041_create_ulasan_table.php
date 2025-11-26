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
    Schema::create('ulasan', function (Blueprint $table) {
        $table->id('id_ulasan');
        
        $table->foreignId('id_pengguna')
              ->references('id_pengguna')->on('pengguna')
              ->onDelete('cascade');

        $table->foreignId('id_booking')
              ->unique() // Satu booking satu ulasan
              ->references('id_booking')->on('booking')
              ->onDelete('cascade');

        $table->unsignedTinyInteger('rating'); // 1-5
        $table->text('komentar')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};