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
        Schema::create('setting_liburs', function (Blueprint $table) {
            $table->id();
            $table->string('bulan', 7)->unique(); // Y-m
            $table->string('tanggal_merah')->nullable(); // format "1,3,15"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_liburs');
    }
};
