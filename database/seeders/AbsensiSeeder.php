<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('absensis')->insert([
            [
                'pegawai_id' => 1,
                'pegawai_name' => 'Budi Santoso',
                'status' => 'Hadir',
                'attendance_photo' => 'foto_budi.jpg',
                'attendance_time' => now()->subDays(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_id' => 2,
                'pegawai_name' => 'Siti Aminah',
                'status' => 'Izin',
                'attendance_photo' => null,
                'attendance_time' => now()->subDays(1),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
