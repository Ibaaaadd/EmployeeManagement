<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('penggajians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
        $table->enum('periode', ['1', '2']);  // Periode 1: 1-15, 2: 16-akhir bulan
        $table->integer('insentif')->default(0);
        $table->integer('potongan')->default(0);
        $table->integer('total_gaji')->default(0);
        $table->timestamps();
    });
}

};
