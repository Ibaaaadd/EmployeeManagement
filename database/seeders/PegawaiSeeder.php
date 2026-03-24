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
        $data = [];

        // Generate 20 pegawai
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'name' => $faker->name,
                'alamat' => $faker->address,
                'nomor' => $faker->phoneNumber,
                'jabatan' => $faker->randomElement(['Staff HRD', 'Admin Keuangan', 'Programmer', 'Manager', 'Marketing', 'Customer Service']),
                'gaji' => $faker->randomElement([4500000, 5000000, 6000000, 7500000, 9000000]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('pegawais')->insert($data);
    }
}
