<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingLiburSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('setting_liburs')->truncate();
        
        $currentYear = Carbon::now()->year;
        
        // Libur nasional Indonesia 2026 (gabungkan per bulan)
        $holidays = [
            $currentYear . '-01' => '1',                    // Tahun Baru
            $currentYear . '-02' => '17',                   // Imlek
            $currentYear . '-03' => '11,22,29',             // Nyepi, Isra Miraj, Jumat Agung
            $currentYear . '-04' => '3,4,5,18,19,20',       // Paskah, Idul Fitri & cuti bersama
            $currentYear . '-05' => '1,16,23',              // Buruh, Waisak, Kenaikan Isa
            $currentYear . '-06' => '1,26',                 // Pancasila, Idul Adha
            $currentYear . '-07' => '17',                   // Tahun Baru Islam
            $currentYear . '-08' => '17',                   // Kemerdekaan RI
            $currentYear . '-09' => '25',                   // Maulid Nabi
            $currentYear . '-12' => '25,26',                // Natal
        ];

        $data = [];
        foreach ($holidays as $bulan => $tanggal_merah) {
            $data[] = [
                'bulan' => $bulan,
                'tanggal_merah' => $tanggal_merah,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert semua data
        DB::table('setting_liburs')->insert($data);
        
        $this->command->info('✅ ' . count($data) . ' bulan dengan tanggal merah berhasil di-seed!');
        $this->command->info('📅 Tahun: ' . $currentYear);
    }
}
