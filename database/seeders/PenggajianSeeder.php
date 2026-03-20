<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenggajianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('penggajians')->insert([
            [
                'pegawai_id' => 1,
                'periode' => '1',
                'insentif' => 500000,
                'potongan' => 100000,
                'total_gaji' => 5400000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pegawai_id' => 2,
                'periode' => '2',
                'insentif' => 300000,
                'potongan' => 50000,
                'total_gaji' => 4750000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
