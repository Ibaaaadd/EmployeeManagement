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
        Schema::create('riwayat_gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->string('periode');
            $table->date('tanggal');
            $table->integer('gaji_pokok');
            $table->integer('insentif');
            $table->integer('potongan');
            $table->integer('total_gaji');
            $table->integer('jumlah_hadir')->default(0);
            $table->integer('jumlah_izin')->default(0);
            $table->integer('jumlah_tidak_hadir')->default(0);
            $table->integer('jumlah_terlambat')->default(0);
            $table->integer('total_hari_kerja')->default(0);
            $table->integer('gaji_per_hari')->default(0);
            $table->string('tanggal_merah')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_gajis');
    }
};
