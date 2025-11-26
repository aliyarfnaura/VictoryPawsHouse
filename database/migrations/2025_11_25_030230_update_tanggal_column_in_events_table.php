<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('events', function (Blueprint $table) {
        $table->dateTime('tanggal')->change();
    });
}

public function down()
{
    Schema::table('events', function (Blueprint $table) {
        $table->date('tanggal')->change(); // kembalikan seperti awal (opsional)
    });
}
};
