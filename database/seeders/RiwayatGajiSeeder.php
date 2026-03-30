<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;
use App\Http\Controllers\PenggajianController;
use Carbon\Carbon;

class RiwayatGajiSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('riwayat_gajis')->truncate();
        
        $this->command->info('🔄 Generating riwayat gaji data...');
        
        $pegawais = Pegawai::all();
        $penggajianController = new PenggajianController();
        
        // Generate untuk 2 bulan ke belakang sampai bulan ini
        $months = [
            Carbon::now()->subMonths(2)->startOfMonth(),
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->startOfMonth(),
        ];
        
        $totalGenerated = 0;
        
        foreach ($pegawais as $pegawai) {
            foreach ($months as $month) {
                try {
                    // Call the updateGajiBulan method untuk auto-calculate
                    $penggajianController->updateGajiBulan($pegawai->id, $month);
                    $totalGenerated++;
                } catch (\Exception $e) {
                    $this->command->error("Error untuk pegawai {$pegawai->name} bulan {$month->format('Y-m')}: " . $e->getMessage());
                }
            }
        }
        
        $this->command->info('✅ ' . $totalGenerated . ' data riwayat gaji berhasil di-generate!');
        $this->command->info('📊 ' . count($pegawais) . ' pegawai x 3 bulan');
    }
}
