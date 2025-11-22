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
    Schema::table('booking', function (Blueprint $table) {
        // Tipe 'time', dan nullable (boleh kosong)
        $table->time('jam_booking')->nullable()->after('jadwal');
    });
}

public function down(): void
{
    Schema::table('booking', function (Blueprint $table) {
        $table->dropColumn('jam_booking');
    });
}
};