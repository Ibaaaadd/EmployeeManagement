<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Absensi;
use App\Models\RiwayatGaji;
use App\Models\SettingLibur;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pegawai
        $totalPegawai = Pegawai::count();
        
        // Kehadiran Hari Ini
        $today = Carbon::today();
        $hadirHariIni = Absensi::whereDate('attendance_time', $today)
            ->where('status', 'Hadir')
            ->count();
        
        // Kehadiran Bulan Ini
        $hadirBulanIni = Absensi::whereMonth('attendance_time', $today->month)
            ->whereYear('attendance_time', $today->year)
            ->where('status', 'Hadir')
            ->count();
        
        // Statistik Status Hari Ini
        $statusHariIni = Absensi::whereDate('attendance_time', $today)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $izinHariIni = $statusHariIni['Izin'] ?? 0;
        $alphaHariIni = $statusHariIni['Alpha'] ?? 0;
        $telatHariIni = Absensi::whereDate('attendance_time', $today)
            ->where('status', 'Hadir')
            ->whereTime('attendance_time', '>', '08:00:00')
            ->count();
        
        // Total Gaji Bulan Ini
        $totalGajiBulanIni = RiwayatGaji::whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year)
            ->sum('total_gaji');
        
        // Pegawai dengan Gaji Tertinggi Bulan Ini
        $topEarners = RiwayatGaji::with('pegawai')
            ->whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year)
            ->orderBy('total_gaji', 'desc')
            ->limit(5)
            ->get();
        
        // Tanggal Merah Bulan Ini
        $bulanIni = $today->format('Y-m');
        $tanggalMerahBulanIni = SettingLibur::where('bulan', $bulanIni)->first();
        $jumlahTanggalMerah = 0;
        if ($tanggalMerahBulanIni && $tanggalMerahBulanIni->tanggal_merah) {
            $jumlahTanggalMerah = count(explode(',', $tanggalMerahBulanIni->tanggal_merah));
        }
        
        // Chart Data - 7 Hari Terakhir
        $chartLabels = [];
        $chartDataHadir = [];
        $chartDataIzin = [];
        $chartDataAlpha = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            
            $hadir = Absensi::whereDate('attendance_time', $date)->where('status', 'Hadir')->count();
            $izin = Absensi::whereDate('attendance_time', $date)->where('status', 'Izin')->count();
            $alpha = Absensi::whereDate('attendance_time', $date)->where('status', 'Alpha')->count();
            
            $chartDataHadir[] = $hadir;
            $chartDataIzin[] = $izin;
            $chartDataAlpha[] = $alpha;
        }
        
        // Statistik Bulan Ini
        $statusBulanIni = Absensi::whereMonth('attendance_time', $today->month)
            ->whereYear('attendance_time', $today->year)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $hadirBulanIniTotal = $statusBulanIni['Hadir'] ?? 0;
        $izinBulanIniTotal = $statusBulanIni['Izin'] ?? 0;
        $alphaBulanIniTotal = $statusBulanIni['Alpha'] ?? 0;
        
        // Pegawai Terlambat Hari Ini
        $telatList = Absensi::with('pegawai')
            ->whereDate('attendance_time', $today)
            ->where('status', 'Hadir')
            ->whereTime('attendance_time', '>', '08:00:00')
            ->orderBy('attendance_time', 'desc')
            ->limit(5)
            ->get();
        
        // Recent Activities (5 Absensi Terbaru Hari Ini)
        $recentActivities = Absensi::with('pegawai')
            ->whereDate('attendance_time', $today)
            ->orderBy('attendance_time', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalPegawai',
            'hadirHariIni',
            'hadirBulanIni',
            'izinHariIni',
            'alphaHariIni',
            'telatHariIni',
            'totalGajiBulanIni',
            'topEarners',
            'jumlahTanggalMerah',
            'tanggalMerahBulanIni',
            'chartLabels',
            'chartDataHadir',
            'chartDataIzin',
            'chartDataAlpha',
            'hadirBulanIniTotal',
            'izinBulanIniTotal',
            'alphaBulanIniTotal',
            'telatList',
            'recentActivities'
        ));
    }
}
