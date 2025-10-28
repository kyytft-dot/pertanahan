<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->bigInteger('NIK')->primary();
            $table->bigInteger('NOMOR_KK');
            $table->string('NAMA', 30);
            $table->string('ALAMAT', 100);
            $table->date('TGL_LAHIR');
            $table->string('NO_TELP', 30);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penduduk');
    }
};