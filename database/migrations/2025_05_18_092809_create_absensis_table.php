<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel absensi.
     */
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();  // Relasi dengan pegawais
            $table->string('status');  // Status kehadiran: Hadir, Izin, Tanpa Keterangan
            $table->string('attendance_photo')->nullable();  // Foto absensi (jika Hadir)
            $table->timestamp('attendance_time');  // Waktu absensi
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migration (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
