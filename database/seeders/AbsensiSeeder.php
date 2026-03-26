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
                    $timestamp = $currentDate->copy()->setTime(random_int(7, 9), random_int(0, 59), 0);
                    
                    $data[] = [
                        'pegawai_id' => $pegawai->id,
                        'pegawai_name' => $pegawai->name,
                        'status' => $status,
                        'attendance_photo' => $status === 'Hadir' ? 'foto_dummy.jpg' : null,
                        'attendance_time' => $timestamp,
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
