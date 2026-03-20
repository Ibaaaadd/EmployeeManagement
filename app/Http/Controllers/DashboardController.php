<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = Pegawai::count();
        $hadirHariIni = Absensi::whereDate('attendance_time', today())->where('status', 'Hadir')->count();
        
        return view('dashboard', compact('totalPegawai', 'hadirHariIni'));
    }
}
