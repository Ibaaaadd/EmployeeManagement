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

        // Generate data absensi untuk sebulan terakhir (maret 2026 atau sesuai now) 
        // sebanyak 20 hari kerja untuk tiap pegawai
        foreach ($pegawais as $pegawai) {
            for ($i = 0; $i < 20; $i++) {
                $status = $faker->randomElement(['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Izin', 'Tidak Hadir']); // presentase hadir lebih besar
                $timestamp = Carbon::now()->subDays(random_int(1, 40))->setTime(random_int(7, 9), random_int(0, 59), 0);
                
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
        }

        // Chunking insert agar tidak terlalu berat jika banyak
        $chunks = array_chunk($data, 100);
        foreach ($chunks as $chunk) {
            DB::table('absensis')->insert($chunk);
        }
    }
}
