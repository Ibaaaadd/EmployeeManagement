<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Pegawai;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $pegawais = Pegawai::all();
        
        // Clear existing data
        DB::table('absensis')->truncate();
        
        $data = [];

        // Generate data absensi untuk 2 bulan ke belakang
        $startDate = Carbon::now()->subMonths(2)->startOfMonth();
        $endDate = Carbon::now();

        $this->command->info('🔄 Generating absensi data...');

        foreach ($pegawais as $pegawai) {
            $currentDate = $startDate->copy();
            $absenCount = 0;
            
            while ($currentDate <= $endDate) {
                // Hanya hari kerja (Senin-Jumat)
                if ($currentDate->isWeekday()) {
                    // 85% Hadir, 10% Izin, 5% Tidak Hadir
                    $rand = rand(1, 100);
                    if ($rand <= 85) {
                        $status = 'Hadir';
                    } elseif ($rand <= 95) {
                        $status = 'Izin';
                    } else {
                        $status = 'Tidak Hadir';
                    }
                    
                    // Random jam masuk antara 07:00 - 09:30
                    $jamMasukHour = rand(7, 9);
                    $jamMasukMin = rand(0, 59);
                    
                    // Jika jam > 9 atau (jam 9 dan menit > 0), maka skip ke jam 8
                    if ($jamMasukHour > 9 || ($jamMasukHour === 9 && $jamMasukMin > 30)) {
                        $jamMasukHour = 8;
                        $jamMasukMin = rand(0, 30);
                    }
                    
                    $timestamp = $currentDate->copy()->setTime($jamMasukHour, $jamMasukMin, 0);
                    
                    $jamMasukStr = null;
                    $jamPulangStr = null;
                    $isLate = false;
                    $attendancePhoto = null;
                    $attendancePhotoPulang = null;

                    if ($status === 'Hadir') {
                        $jamMasukStr = sprintf('%02d:%02d:00', $jamMasukHour, $jamMasukMin);
                        
                        // Jam pulang antara 16:00 - 18:00
                        $jamPulangHour = rand(16, 18);
                        $jamPulangMin = rand(0, 59);
                        $jamPulangStr = sprintf('%02d:%02d:00', $jamPulangHour, $jamPulangMin);
                        
                        $attendancePhoto = 'attendance_photos/dummy_masuk.png';
                        $attendancePhotoPulang = 'attendance_photos/dummy_pulang.png';
                        
                        // Cek telat (> 08:00)
                        if ($jamMasukHour > 8 || ($jamMasukHour === 8 && $jamMasukMin > 0)) {
                            $isLate = true;
                        }
                    }

                    $data[] = [
                        'pegawai_id' => $pegawai->id,
                        'pegawai_name' => $pegawai->name,
                        'status' => $status,
                        'attendance_photo' => $attendancePhoto,
                        'attendance_photo_pulang' => $attendancePhotoPulang,
                        'attendance_time' => $timestamp,
                        'jam_masuk' => $jamMasukStr,
                        'jam_pulang' => $jamPulangStr,
                        'is_late' => $isLate,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                    
                    $absenCount++;
                }
                $currentDate->addDay();
            }
        }

        // Chunking insert agar tidak terlalu berat
        $chunks = array_chunk($data, 200);
        foreach ($chunks as $chunk) {
            DB::table('absensis')->insert($chunk);
        }
        
        $this->command->info('✅ ' . count($data) . ' data absensi berhasil di-seed!');
        $this->command->info('📅 Periode: ' . $startDate->format('Y-m-d') . ' s/d ' . $endDate->format('Y-m-d'));
    }
}
