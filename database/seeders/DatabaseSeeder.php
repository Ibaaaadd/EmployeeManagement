<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->info('═══════════════════════════════════════════════════════');
        
        $this->call([
            UserSeeder::class,              // 1. Users (untuk login)
            PegawaiSeeder::class,           // 2. Pegawai (master data)
            SettingLiburSeeder::class,      // 3. Setting Libur (tanggal merah)
            AbsensiSeeder::class,           // 4. Absensi (2 bulan data)
            RiwayatGajiSeeder::class,       // 5. Riwayat Gaji (auto-calculate dari absensi)
        ]);
        
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📝 Login Credentials:');
        $this->command->info('   Email: admin@example.com');
        $this->command->info('   Password: password');
        $this->command->info('');
    }
}
