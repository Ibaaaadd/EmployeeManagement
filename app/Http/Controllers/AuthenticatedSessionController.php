<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    // Menampilkan halaman login
    public function create()
    {
        return view('auth.login');
    }

    // Menangani proses login
    public function store(Request $request)
    {
        // Validasi email dan password
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah pengguna pertama kali ada di database
        $user = User::first();

        if (!$user) {
            // Buat pengguna admin pertama kali jika belum ada
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin', // Set role admin untuk user pertama
            ]);
        }

        // Verifikasi login
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // Redirect berdasarkan role
            if ($user && $user->role === 'admin') {
                return redirect()->route('pegawai.index'); // Admin ke halaman pegawai
            } else {
                return redirect()->route('absensi.index'); // User biasa ke absensi
            }
        }

        // Jika login gagal, kembali dengan error
        return back()->withErrors([
            'login' => 'Maaf, email atau password yang Anda masukkan tidak tepat.',
                ]);
    }

    // Logout
    public function destroy(Request $request)
    {
        Auth::logout();
        return redirect('/login');  // Redirect ke halaman login setelah logout
    }
}
