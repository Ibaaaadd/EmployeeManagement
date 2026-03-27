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
        $data = [];

// Generate data absensi untuk 30 hari ke belakang
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($pegawais as $pegawai) {
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                if ($currentDate->isWeekday()) {
                    $status = $faker->randomElement(['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Izin', 'Tidak Hadir']);
                    
                    $jamMasukHour = random_int(7, 9);
                    $jamMasukMin = random_int(0, 59);
                    $timestamp = $currentDate->copy()->setTime(8, 0, 0); // Default attendance_time bisa diset ke jam awal masuk
                    
                    $jamMasukStr = null;
                    $jamPulangStr = null;
                    $isLate = false;
                    $attendancePhoto = null;
                    $attendancePhotoPulang = null;

                    if ($status === 'Hadir') {
                        $jamMasukStr = sprintf('%02d:%02d:00', $jamMasukHour, $jamMasukMin);
                        $jamPulangHour = random_int(16, 18);
                        $jamPulangMin = random_int(0, 59);
                        $jamPulangStr = sprintf('%02d:%02d:00', $jamPulangHour, $jamPulangMin);
                        $attendancePhoto = 'foto_dummy.jpg';
                        $attendancePhotoPulang = 'foto_dummy_pulang.jpg';
                        
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
                }
                $currentDate->addDay();
            }
        }

        // Chunking insert agar tidak terlalu berat jika banyak
        $chunks = array_chunk($data, 100);
        foreach ($chunks as $chunk) {
            DB::table('absensis')->insert($chunk);
        }
    }
}
