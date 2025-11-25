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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');

            $table->foreignId('id_booking')
                ->references('id_booking')->on('booking')
                ->onDelete('cascade');

            $table->string('metode');
            $table->string('bukti_gambar');

            // Sesuai SQL: tanggal_pembayaran default CURRENT_TIMESTAMP
            $table->dateTime('tanggal_pembayaran')->useCurrent();

            // Sesuai SQL: updated_at ON UPDATE CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};