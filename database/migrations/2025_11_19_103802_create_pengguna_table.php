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
    Schema::create('pengguna', function (Blueprint $table) {
        $table->id('id_pengguna');
        $table->string('username');
        $table->string('email')->unique();
        $table->string('password');
        $table->enum('role', ['admin', 'pelanggan'])->default('pelanggan');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
