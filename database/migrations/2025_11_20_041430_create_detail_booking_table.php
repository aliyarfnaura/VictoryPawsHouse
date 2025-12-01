<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_booking', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_booking')
                ->constrained('booking', 'id_booking')
                ->onDelete('cascade');
            $table->foreignId('id_layanan')
                ->constrained('layanan', 'id_layanan')
                ->onDelete('cascade');
            $table->decimal('harga_saat_ini', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_booking');
    }
};