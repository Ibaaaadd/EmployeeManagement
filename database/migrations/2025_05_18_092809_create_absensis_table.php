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
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->string('pegawai_name')->nullable();
            $table->string('status');
            $table->string('attendance_photo')->nullable();
            $table->string('attendance_photo_pulang')->nullable();
            $table->timestamp('attendance_time')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->boolean('is_late')->default(false);
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
