<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Creating users...');
        
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',  // PENTING: Set role admin
                'pegawai_id' => null, // Admin tidak perlu pegawai_id
                'remember_token' => Str::random(10),
            ]
        );

        // User biasa (linked ke pegawai jika ada)
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User Demo',
                'email' => 'user@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',  // PENTING: Set role user
                'pegawai_id' => null, // Bisa diset ke pegawai tertentu jika diperlukan
                'remember_token' => Str::random(10),
            ]
        );
        
        $this->command->info('✅ 2 users berhasil di-seed!');
        $this->command->info('   👤 ADMIN: admin@example.com / password (role: admin)');
        $this->command->info('   👤 USER:  user@example.com / password (role: user)');
    }
}
