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

        // Buat akun user untuk setiap pegawai
        $pegawais = \App\Models\Pegawai::all();
        $count = 0;

        foreach ($pegawais as $pegawai) {
            // Ubah nama menjadi format email, contoh "budi1@example.com"
            $firstName = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(' ', $pegawai->name)[0]));
            $email = $firstName . $pegawai->id . '@example.com';

            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $pegawai->name,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'user',  
                    'pegawai_id' => $pegawai->id, 
                    'remember_token' => Str::random(10),
                ]
            );
            $count++;
        }
        
        $this->command->info('✅ ' . ($count + 1) . ' users berhasil di-seed!');
        $this->command->info('   👤 ADMIN: admin@example.com / password (role: admin)');
        
        if ($pegawais->isNotEmpty()) {
            $firstUserEmail = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(' ', $pegawais->first()->name)[0])) . $pegawais->first()->id . '@example.com';
            $this->command->info("   👤 CONTOH USER: $firstUserEmail / password (role: user)");
        }
    }
}
