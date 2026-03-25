<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Absensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = Pegawai::count();
        $hadirHariIni = Absensi::whereDate('attendance_time', today())->where('status', 'Hadir')->count();
        
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $hadir = Absensi::whereDate('attendance_time', $date)->where('status', 'Hadir')->count();
            $chartData[] = $hadir;
        }

        return view('dashboard', compact('totalPegawai', 'hadirHariIni', 'chartLabels', 'chartData'));
    }
}
