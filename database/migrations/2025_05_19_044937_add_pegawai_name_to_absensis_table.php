<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPegawaiNameToAbsensisTable extends Migration
{
    public function up()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->string('pegawai_name')->after('pegawai_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('pegawai_name');
        });
    }
}
