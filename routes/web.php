<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\SettingLiburController;
use App\Http\Controllers\AuthenticatedSessionController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/absensi', function () {
    return view('absensi'); // Halaman absensi
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pengaturan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/pengaturan', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/daftar-pegawai', [PegawaiController::class, 'showAll'])->name('pegawai.showAll'); 
    Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    Route::get('/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::get('/absensi', [AbsensiController::class, 'create'])->name('absensi.index');
    Route::get('/api/pegawai-belum-digaji', [PenggajianController::class, 'getPegawaiBelumDigaji'])->name('api.pegawai.belumdigaji');
    
    // Old routes (keep for backward compatibility)
    Route::get('/penggajian', [PenggajianController::class, 'index'])->name('gaji.index');
    Route::get('/penggajian/{id}', [PenggajianController::class, 'show'])->name('gaji.show');
    Route::get('/riwayat-gaji', [PenggajianController::class, 'riwayat'])->name('gaji.riwayat');
    
    // New unified salary history routes
    Route::get('/histori-gaji', [PenggajianController::class, 'historiGajiIndex'])->name('histori-gaji.index');
    Route::get('/histori-gaji/{id}', [PenggajianController::class, 'historiGajiDetail'])->name('histori-gaji.detail');
    
    Route::get('/riwayat/{id}/preview', [PenggajianController::class, 'previewSlip'])->name('riwayat.preview');
    Route::delete('/riwayat-gaji/{id}', [PenggajianController::class, 'destroyRiwayat'])->name('riwayat.destroy');
    Route::get('/riwayat-gaji/download/{id}', [PenggajianController::class, 'downloadFromRiwayat'])->name('riwayat.download');
    Route::get('/riwayat-absensi', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
    Route::get('/riwayat-absensi/download', [AbsensiController::class, 'downloadPdf'])->name('absensi.riwayat.download');
    
    // Setting Tanggal Merah routes
    Route::get('/setting-libur', [SettingLiburController::class, 'index'])->name('setting-libur.index');
    Route::post('/setting-libur', [SettingLiburController::class, 'store'])->name('setting-libur.store');
    Route::delete('/setting-libur/{bulan}', [SettingLiburController::class, 'destroy'])->name('setting-libur.destroy');
});

// Menyimpan pegawai
Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');

// Menampilkan daftar pegawai
Route::get('/daftar-pegawai', [PegawaiController::class, 'showAll'])->name('pegawai.showAll'); // Untuk menampilkan semua pegawai

// Route untuk edit pegawai
Route::get('/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');

// Route untuk update pegawai
Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');

// Route untuk hapus pegawai


Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index'); // Menampilkan form absensi
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store'); // Menyimpan data absensi

Route::get('/penggajian', [PenggajianController::class, 'index'])->name('gaji.index');
Route::get('/penggajian/{id}', [PenggajianController::class, 'show'])->name('gaji.show');

Route::get('/riwayat-gaji', [PenggajianController::class, 'riwayat'])->name('gaji.riwayat');

Route::get('/slipgaji/{id}', [PenggajianController::class, 'generateSlipGaji'])->name('slipgaji.pdf');

Route::post('/slip-gaji/download', [PenggajianController::class, 'downloadSlip'])->name('slipgaji.download');
Route::get('/riwayat-gaji/download/{id}', [PenggajianController::class, 'downloadFromRiwayat'])->name('riwayat.download');

Route::get('/riwayat-absensi', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
Route::get('/riwayat-absensi/download', [AbsensiController::class, 'downloadPdf'])->name('absensi.riwayat.download');
