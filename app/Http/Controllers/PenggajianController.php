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
            'periode' => 'required|in:1,2',
            'bulan' => 'required|date_format:Y-m',
        ]);

        $pegawaiId = $request->pegawai_id;
        $periode = $request->periode;
        $bulanTahun = $request->bulan;

        if ($periode == '1') {
            $startDate = "$bulanTahun-01";
            $endDate = "$bulanTahun-15";
        } else {
            $startDate = "$bulanTahun-16";
            $endDate = date("Y-m-t", strtotime($bulanTahun . '-01'));
        }

        $jumlahIzin = Absensi::where('pegawai_id', $pegawaiId)
            ->where('status', 'Izin')
            ->whereBetween('attendance_time', [$startDate, $endDate])
            ->count();

        $jumlahTidakHadir = Absensi::where('pegawai_id', $pegawaiId)
            ->where('status', operator: 'Tidak Hadir')
            ->whereBetween('attendance_time', [$startDate, $endDate])
            ->count();

        $potongan = ($jumlahIzin + $jumlahTidakHadir) * 30000;

        return response()->json([
            'izin' => $jumlahIzin,
            'tidak_hadir' => $jumlahTidakHadir,
            'potongan' => $potongan,
        ]);
    }

   public function hitungGaji(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'periode' => 'required|in:1,2',
            'insentif' => 'required|integer|min:0',
            'bulan' => 'required|date_format:Y-m',
        ]);

        $pegawai = Pegawai::findOrFail($request->pegawai_id);
        $periode = $request->periode;
        $bulanTahun = $request->bulan;

        if ($periode == '1') {
            $startDate = "$bulanTahun-01";
            $endDate = "$bulanTahun-15";
        } else {
            $startDate = "$bulanTahun-16";
            $endDate = date("Y-m-t", strtotime($bulanTahun . '-01'));
        }

        $jumlahIzin = Absensi::where('pegawai_id', $pegawai->id)
            ->where('status', 'Izin')
            ->whereBetween('attendance_time', [$startDate, $endDate])
            ->count();

        $jumlahTidakHadir = Absensi::where('pegawai_id', $pegawai->id)
            ->where('status', 'Tidak Hadir')
            ->whereBetween('attendance_time', [$startDate, $endDate])
            ->count();

        $gajiPokok = $pegawai->gaji;
        $insentif = $request->insentif;
        $totalPengurangan = ($jumlahIzin + $jumlahTidakHadir) * 30000;
        $totalGaji = $gajiPokok - $totalPengurangan + $insentif;

        RiwayatGaji::create([
        'pegawai_id' => $pegawai->id,
        'periode' => $periode,
        'tanggal' => now(), 
        'gaji_pokok' => $gajiPokok,
        'insentif' => $insentif,
        'potongan' => $totalPengurangan,
        'total_gaji' => $totalGaji,
        ]);

        $pegawaiList = Pegawai::all();

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
        'bulanTahun'
    ))->with('pegawaiId', $request->pegawai_id);

    }
    
    public function selesaikanPenggajian(Request $request)
    {
    $data = $request->validate([
        'pegawai_id' => 'required|exists:pegawais,id',
        'periode' => 'required|in:1,2',
        'bulan' => 'required|date_format:Y-m',
        'gaji_pokok' => 'required|integer',
        'insentif' => 'required|integer',
        'potongan' => 'required|integer',
        'total_gaji' => 'required|integer',
    ]);

    $pegawai = Pegawai::find($data['pegawai_id']);
    $pdf = Pdf::loadView('slipgaji', compact('pegawai', 'data'));

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

    return view('riwayat', compact('riwayat'));
    }


    public function generateSlipGaji($id)
    {
    $riwayat = \App\Models\RiwayatGaji::with('pegawai')->findOrFail($id);

    $pdf = Pdf::loadView('slipgaji', compact('riwayat'));

    return $pdf->download('SlipGaji_'.$riwayat->pegawai->nama.'_'.date('F_Y', strtotime($riwayat->tanggal)).'.pdf');
    }

    public function downloadSlip(Request $request)
    {
    $pegawai = Pegawai::findOrFail($request->pegawai_id);

    $data = [
        'pegawai' => $pegawai,
        'periode' => $request->periode,
        'bulanTahun' => $request->bulan,
        'gajiPokok' => $request->gaji_pokok,
        'insentif' => $request->insentif,
        'jumlahIzin' => (int)($request->jumlah_izin ?? 0),
        'jumlahTidakHadir' => (int)($request->jumlah_Tidak_Hadir ?? 0),
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
        'jumlahIzin' => $riwayat->jumlah_izin,
        'jumlahTidakHadir' => (int)($request->jumlah_Tidak_Hadir ?? 0),
        'totalPengurangan' => $riwayat->total_pengurangan,
        'totalGaji' => $riwayat->total_gaji,
    ];

    $pdf = Pdf::loadView('slip_pdf', $data);

    return $pdf->download('SlipGaji_' . $riwayat->pegawai->name . '_' . \Carbon\Carbon::parse($riwayat->tanggal_gaji)->format('Ym') . '.pdf');
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
        'totalPengurangan' => $riwayat->potongan ?? 0,
        'totalGaji' => $riwayat->total_gaji,
    ]);
    }

}
