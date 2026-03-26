<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiwayatGajiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('riwayat_gajis')->insert([
            [
                'pegawai_id' => 1,
                'periode' => 'Juni 2025',
                'tanggal' => now()->subDays(20)->format('Y-m-d'),
                'gaji_pokok' => 5000000,
                'insentif' => 500000,
                'potongan' => 100000,
                'total_gaji' => 5400000,
                'jumlah_izin' => 1,
                'jumlah_tidak_hadir' => 0,
                'total_hari_kerja' => 20,
                'gaji_per_hari' => 250000,
                'tanggal_merah' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_id' => 2,
                'periode' => 'Juni 2025',
                'tanggal' => now()->subDays(20)->format('Y-m-d'),
                'gaji_pokok' => 4500000,
                'insentif' => 300000,
                'potongan' => 50000,
                'total_gaji' => 4750000,
                'jumlah_izin' => 0,
                'jumlah_tidak_hadir' => 0,
                'total_hari_kerja' => 20,
                'gaji_per_hari' => 225000,
                'tanggal_merah' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
