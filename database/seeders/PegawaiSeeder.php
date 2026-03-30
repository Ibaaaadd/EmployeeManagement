<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Clear existing data
        DB::table('pegawais')->truncate();
        
        $data = [];

        // Data pegawai yang lebih realistic dengan variasi gaji
        $jabatanGaji = [
            'Direktur' => [15000000, 18000000, 20000000],
            'Manager' => [10000000, 12000000, 13500000],
            'Supervisor' => [7500000, 8500000, 9000000],
            'Staff HRD' => [5000000, 5500000, 6000000],
            'Staff Keuangan' => [5500000, 6000000, 6500000],
            'Programmer' => [7000000, 8000000, 9000000],
            'Marketing' => [5000000, 6000000, 7000000],
            'Customer Service' => [4500000, 5000000, 5500000],
            'Admin' => [4000000, 4500000, 5000000],
            'Security' => [3500000, 4000000, 4500000],
        ];

        // Generate pegawai dengan data yang lebih detail
        foreach ($jabatanGaji as $jabatan => $gajiRange) {
            // Buat 2-3 pegawai per jabatan
            $jumlah = ($jabatan === 'Direktur') ? 1 : rand(2, 3);
            
            for ($i = 0; $i < $jumlah; $i++) {
                $data[] = [
                    'name' => $faker->name,
                    'alamat' => $faker->address,
                    'nomor' => $faker->phoneNumber,
                    'jabatan' => $jabatan,
                    'gaji' => $faker->randomElement($gajiRange),
                    'created_at' => now()->subMonths(rand(1, 12)),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('pegawais')->insert($data);
        
        $this->command->info('✅ ' . count($data) . ' pegawai berhasil di-seed!');
    }
}
