<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pegawais')->insert([
            [
                'name' => 'Budi Santoso',
                'alamat' => 'Jl. Merdeka No.1',
                'nomor' => '081234567890',
                'jabatan' => 'Staff HRD',
                'gaji' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aminah',
                'alamat' => 'Jl. Sudirman No.10',
                'nomor' => '089876543210',
                'jabatan' => 'Admin Keuangan',
                'gaji' => 4500000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
