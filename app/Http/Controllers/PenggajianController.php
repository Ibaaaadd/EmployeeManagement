<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Absensi;
use App\Models\Penggajian;
use App\Models\RiwayatGaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class PenggajianController extends Controller
{
    public function index()
    {
        $pegawaiList = Pegawai::all();
        return view('penggajian', compact('pegawaiList'));
    }

    public function getAbsensiByPegawai(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|date_format:Y-m',
        ]);

        $pegawaiId = $request->pegawai_id;
        $bulanTahun = $request->bulan;

        $startDate = \Carbon\Carbon::parse("$bulanTahun-01");
        $endDate = $startDate->copy()->endOfMonth();

        $attendances = Absensi::where('pegawai_id', $pegawaiId)
            ->whereDate('attendance_time', '>=', $startDate)
            ->whereDate('attendance_time', '<=', $endDate)
            ->get();

        $jumlahIzin = 0;
        $jumlahTidakHadir = 0;

        $totalHariKerja = 0;
        
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            if ($currentDate->isWeekday()) {
                $totalHariKerja++;
                $dateStr = $currentDate->format('Y-m-d');
                $att = $attendances->first(function($item) use ($dateStr) {
                    return \Carbon\Carbon::parse($item->attendance_time)->format('Y-m-d') === $dateStr;
                });

                if ($att) {
                    if ($att->status == 'Izin') {
                        $jumlahIzin++;
                    } elseif ($att->status == 'Tidak Hadir') {
                        $jumlahTidakHadir++;
                    }
                } else {
                    // Kalau tidak absen pada hari kerja, terhitung Tidak Hadir
                    $jumlahTidakHadir++;
                }
            }
            $currentDate->addDay();
        }

        $pegawai = Pegawai::findOrFail($pegawaiId);
        $gajiPerHari = $totalHariKerja > 0 ? floor($pegawai->gaji / $totalHariKerja) : 0;
        $potongan = ($jumlahIzin + $jumlahTidakHadir) * $gajiPerHari;

        return response()->json([
            'izin' => $jumlahIzin,
            'tidak_hadir' => $jumlahTidakHadir,
            'gaji_per_hari' => $gajiPerHari,
            'potongan' => $potongan,
        ]);
    }

   public function hitungGaji(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|date_format:Y-m',
            'tanggal_merah' => 'nullable|string',
        ]);

        $pegawai = Pegawai::findOrFail($request->pegawai_id);
        $bulanTahun = $request->bulan;
        $periode = '1 Bulan Full';
        $insentif = $request->insentif ?? 0;
        
        // Parse tanggal libur
        $liburDates = [];
        if (!empty($request->tanggal_merah)) {
            $tanggals = explode(',', $request->tanggal_merah);
            foreach ($tanggals as $t) {
                $t = trim($t);
                if (is_numeric($t) && $t > 0 && $t <= 31) {
                    $liburDates[] = sprintf('%s-%02d', $bulanTahun, $t);
                }
            }
        }

        $startDate = \Carbon\Carbon::parse("$bulanTahun-01");
        $endDate = $startDate->copy()->endOfMonth();

        $attendances = Absensi::where('pegawai_id', $pegawai->id)
            ->whereDate('attendance_time', '>=', $startDate)
            ->whereDate('attendance_time', '<=', $endDate)
            ->get();

        $jumlahIzin = 0;
        $jumlahTidakHadir = 0;
        $totalHariKerja = 0;
        
        // Hitung total hari kerja (Senin - Jumat) di luar tanggal merah
        $tempDate = $startDate->copy();
        while ($tempDate <= $endDate) {
            if ($tempDate->isWeekday()) {
                if (!in_array($tempDate->format('Y-m-d'), $liburDates)) {
                    $totalHariKerja++;
                }
            }
            $tempDate->addDay();
        }

        $gajiPokok = $pegawai->gaji;
        $gajiPerHari = $totalHariKerja > 0 ? floor($gajiPokok / $totalHariKerja) : 0;

        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            // Evaluasi di hari kerja yang bukan tanggal merah
            if ($currentDate->isWeekday()) {
                $dateStr = $currentDate->format('Y-m-d');
                if (!in_array($dateStr, $liburDates)) {
                    $att = $attendances->first(function($item) use ($dateStr) {
                        return \Carbon\Carbon::parse($item->attendance_time)->format('Y-m-d') === $dateStr;
                    });

                    if ($att) {
                        if ($att->status == 'Izin') {
                            $jumlahIzin++;
                        } elseif ($att->status == 'Tidak Hadir') {
                            $jumlahTidakHadir++;
                        }
                    } else {
                        // Tidak ada record absen di hari kerja = Tidak Hadir
                        $jumlahTidakHadir++;
                    }
                }
            }
            $currentDate->addDay();
        }

        $totalPengurangan = ($jumlahIzin + $jumlahTidakHadir) * $gajiPerHari;
        $totalGaji = $gajiPokok - $totalPengurangan + $insentif;

        RiwayatGaji::create([
        'pegawai_id' => $pegawai->id,
        'periode' => $periode,
        'tanggal' => now(), 
        'gaji_pokok' => $gajiPokok,
        'insentif' => $insentif,
        'potongan' => $totalPengurangan,
        'total_gaji' => $totalGaji,
        'jumlah_izin' => $jumlahIzin,
        'jumlah_tidak_hadir' => $jumlahTidakHadir,
        'total_hari_kerja' => $totalHariKerja,
        'gaji_per_hari' => $gajiPerHari,
        'tanggal_merah' => $request->tanggal_merah,
        ]);

        $pegawaiList = Pegawai::all();
        $hasilHitung = true;

        return view('penggajian', compact(
        'pegawaiList',
        'pegawai',
        'periode',
        'jumlahIzin',
        'jumlahTidakHadir',
        'gajiPokok',
        'insentif',
        'totalPengurangan',
        'totalGaji',
        'bulanTahun',
        'hasilHitung',
        'totalHariKerja',
        'gajiPerHari'
    ))->with('pegawaiId', $request->pegawai_id);

    }
    
    public function selesaikanPenggajian(Request $request)
    {
    $data = $request->validate([
        'pegawai_id' => 'required|exists:pegawais,id',
        'bulan' => 'required|date_format:Y-m',
        'gaji_pokok' => 'required|integer',
        'insentif' => 'required|integer',
        'potongan' => 'required|integer',
        'total_gaji' => 'required|integer',
    ]);

    $data['periode'] = '1 Bulan Full';

    $pegawai = Pegawai::find($data['pegawai_id']);
    // Menyiapkan data untuk pdf
    $viewData = [
        'pegawai' => $pegawai,
        'periode' => $data['periode'],
        'bulanTahun' => $data['bulan'],
        'gajiPokok' => $data['gaji_pokok'],
        'insentif' => $data['insentif'],
        'jumlahIzin' => $request->jumlah_izin ?? 0,
        'jumlahTidakHadir' => $request->jumlah_tidak_hadir ?? 0,
        'gajiPerHari' => $request->gaji_per_hari ?? 30000,
        'totalPengurangan' => $data['potongan'],
        'totalGaji' => $data['total_gaji'],
    ];
    $pdf = Pdf::loadView('slip_pdf', $viewData);

    $pdfName = 'slipgaji_' . $pegawai->id . '_' . now()->format('YmHis') . '.pdf';
    Storage::put("public/slipgaji/$pdfName", $pdf->output());

    RiwayatGaji::create([
        'pegawai_id' => $data['pegawai_id'],
        'periode' => $data['periode'],
        'tanggal' => now(),
        'gaji_pokok' => $data['gaji_pokok'],
        'insentif' => $data['insentif'],
        'potongan' => $data['potongan'],
        'total_gaji' => $data['total_gaji'],
        'pdf_path' => "storage/slipgaji/$pdfName",
    ]);

    return redirect()->route('riwayat-gaji')->with('success', 'Penggajian diselesaikan!');
    }

    public function riwayat(Request $request)
    {
    $query = RiwayatGaji::with('pegawai');

    if ($request->filled('nama')) {
        $query->whereHas('pegawai', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->nama . '%');
        });
    }
    if ($request->filled('bulan')) {
        $tanggal = \Carbon\Carbon::parse($request->bulan);
        $query->whereMonth('tanggal', $tanggal->month)
              ->whereYear('tanggal', $tanggal->year);
    }

    $riwayat = $query->orderBy('created_at', 'desc')->get();
    $pegawaiList = \App\Models\Pegawai::all();

    return view('riwayat', compact('riwayat', 'pegawaiList'));
    }


    public function generateSlipGaji($id)
    {
    $riwayat = \App\Models\RiwayatGaji::with('pegawai')->findOrFail($id);

    $viewData = [
        'pegawai' => $riwayat->pegawai,
        'periode' => $riwayat->periode,
        'bulanTahun' => $riwayat->tanggal,
        'gajiPokok' => $riwayat->gaji_pokok,
        'insentif' => $riwayat->insentif,
        'jumlahIzin' => $riwayat->jumlah_izin ?? 0,
        'jumlahTidakHadir' => $riwayat->jumlah_tidak_hadir ?? 0,
        'gajiPerHari' => $riwayat->gaji_per_hari ?? 30000,
        'totalPengurangan' => $riwayat->potongan ?? 0,
        'totalGaji' => $riwayat->total_gaji,
    ];
    $pdf = Pdf::loadView('slip_pdf', $viewData);

    return $pdf->download('SlipGaji_'.$riwayat->pegawai->name.'_'.date('F_Y', strtotime($riwayat->tanggal)).'.pdf');
    }

    public function downloadSlip(Request $request)
    {
    $pegawai = Pegawai::findOrFail($request->pegawai_id);

    $data = [
        'pegawai' => $pegawai,
        'periode' => '1 Bulan Full',
        'bulanTahun' => $request->bulan,
        'gajiPokok' => $request->gaji_pokok,
        'insentif' => $request->insentif,
        'jumlahIzin' => (int)($request->jumlah_izin ?? 0),
        'jumlahTidakHadir' => (int)($request->jumlah_tidak_hadir ?? 0),
        'gajiPerHari' => (int)($request->gaji_per_hari ?? 30000),
        'totalPengurangan' => $request->potongan,
        'totalGaji' => $request->total_gaji,
    ];

    $pdf = Pdf::loadView('slip_pdf', $data);
    return $pdf->download('SlipGaji_' . $pegawai->name . '_' . now()->format('Ym') . '.pdf');
    }

    public function downloadFromRiwayat($id)
    {
    $riwayat = RiwayatGaji::with('pegawai')->findOrFail($id);

    $data = [
        'pegawai' => $riwayat->pegawai,
        'periode' => $riwayat->periode,
        'bulanTahun' => $riwayat->tanggal,
        'gajiPokok' => $riwayat->gaji_pokok,
        'insentif' => $riwayat->insentif,
        'jumlahIzin' => $riwayat->jumlah_izin ?? 0,
        'jumlahTidakHadir' => $riwayat->jumlah_tidak_hadir ?? 0,
        'gajiPerHari' => $riwayat->gaji_per_hari ?? 0,
        'totalPengurangan' => $riwayat->potongan,
        'totalGaji' => $riwayat->total_gaji,
    ];

    $pdf = Pdf::loadView('slip_pdf', $data);

    return $pdf->download('SlipGaji_' . $riwayat->pegawai->name . '_' . \Carbon\Carbon::parse($riwayat->tanggal)->format('Ym') . '.pdf');
    }
    public function previewSlip($id)
    {
    $riwayat = RiwayatGaji::with('pegawai')->findOrFail($id);

    return view('slip_web', [
        'pegawai' => $riwayat->pegawai,
        'periode' => $riwayat->periode,
        'bulanTahun' => $riwayat->tanggal,
        'gajiPokok' => $riwayat->gaji_pokok,
        'insentif' => $riwayat->insentif,
        'jumlahIzin' => $riwayat->jumlah_izin ?? 0,
        'jumlahTidakHadir' => $riwayat->jumlah_tidak_hadir ?? 0,
        'gajiPerHari' => $riwayat->gaji_per_hari ?? 0,
        'totalPengurangan' => $riwayat->potongan ?? 0,
        'totalGaji' => $riwayat->total_gaji,
    ]);
    }

}
