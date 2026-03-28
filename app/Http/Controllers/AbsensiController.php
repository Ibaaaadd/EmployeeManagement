<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class AbsensiController extends Controller
{
    // Menampilkan halaman absensi
    public function index()
    {
        // $pegawaiList = Pegawai::all(); // Ambil semua data pegawai
        // return view('absensi', compact('pegawaiList'));
        $today = Carbon::today()->toDateString();

        $sudahAbsenIds = Absensi::whereDate('attendance_time', $today)->pluck('pegawai_id');

        $pegawaiBelumAbsen = Pegawai::whereNotIn('id', $sudahAbsenIds)->paginate(10);

        return view('absensi', [
            'pegawaiList' => Pegawai::all(),
            'pegawaiBelumAbsen' => $pegawaiBelumAbsen,
        ]); // Kirim data pegawai ke view
    }

    // Menyimpan data absensi
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'employee_id' => 'required|exists:pegawais,id',
            'status' => 'required|in:Hadir,Izin,Tidak Hadir',
            'tipe_absen' => 'required_if:status,Hadir|in:masuk,pulang',
            'attendance_photo' => 'nullable|string', // Validasi base64 string
            'attendance_time' => 'required|date',
        ]);

        // Cari pegawai berdasarkan ID
        $pegawai = Pegawai::findOrFail($request->employee_id);
        $timeReq = \Carbon\Carbon::parse($request->attendance_time);
        $today = $timeReq->toDateString();

        $absensi = Absensi::where('pegawai_id', $pegawai->id)
                          ->whereDate('attendance_time', $today)
                          ->first();

        // Handle foto
        $photoPath = null;
        if ($request->status == 'Hadir' && $request->has('attendance_photo') && !empty($request->attendance_photo)) {
            $photo = $request->attendance_photo;
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photo));
            // Tambahkan flag tipe absen di nama file agar tidak overwrite
            $fileName = 'attendance_' . time() . '_' . $request->tipe_absen . '.png';
            Storage::disk('public')->put('attendance_photos/' . $fileName, $image);
            $photoPath = 'attendance_photos/' . $fileName;
        }

        if ($request->status == 'Hadir') {
            if ($request->tipe_absen == 'masuk') {
                if ($absensi && $absensi->jam_masuk) {
                    return redirect()->back()->with('error', 'Pegawai sudah melakukan absen masuk hari ini.');
                }
                
                if (!$absensi) {
                    $absensi = new Absensi();
                    $absensi->pegawai_id = $pegawai->id;
                    $absensi->pegawai_name = $pegawai->name;
                    $absensi->status = 'Hadir';
                    $absensi->attendance_time = $request->attendance_time;
                }
                
                $absensi->jam_masuk = $timeReq->format('H:i:s');
                if ($photoPath) $absensi->attendance_photo = $photoPath;

                // Cek keterlambatan (> 08:00)
                $cutoff = \Carbon\Carbon::parse($today . ' 08:00:00');
                $absensi->is_late = $timeReq->greaterThan($cutoff);
                
                $absensi->save();
            } else if ($request->tipe_absen == 'pulang') {
                if (!$absensi || !$absensi->jam_masuk) {
                    return redirect()->back()->with('error', 'Pegawai belum absen masuk hari ini.');
                }
                if ($absensi->jam_pulang) {
                    return redirect()->back()->with('error', 'Pegawai sudah melakukan absen pulang hari ini.');
                }

                $absensi->jam_pulang = $timeReq->format('H:i:s');
                if ($photoPath) $absensi->attendance_photo_pulang = $photoPath;
                $absensi->save();
            }
        } else {
            // Izin atau Tidak Hadir
            if ($absensi) {
                return redirect()->back()->with('error', 'Data absensi hari ini sudah ada.');
            }
            $absensi = new Absensi();
            $absensi->pegawai_id = $pegawai->id;
            $absensi->pegawai_name = $pegawai->name;
            $absensi->status = $request->status;
            $absensi->attendance_time = $request->attendance_time;
            $absensi->save();
        }

        // Otomatis update/create history gaji untuk bulan ini
        app(PenggajianController::class)->updateGajiBulan($pegawai->id, \Carbon\Carbon::parse($request->attendance_time));

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dilakukan!');
    }

    public function riwayat(Request $request)
{
    $query = Absensi::with('pegawai');

    if ($request->filled('nama')) {
        $query->whereHas('pegawai', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->nama . '%');
        });
    }

    $bulan = $request->input('bulan', \Carbon\Carbon::now()->format('Y-m'));
        $tanggal = \Carbon\Carbon::parse($bulan);
        $query->whereMonth('attendance_time', $tanggal->month)
              ->whereYear('attendance_time', $tanggal->year);

    $query->orderBy('attendance_time', 'desc');
    $semuaAbsensi = $query->get();

    // ========== Rekap Per Pegawai ==========
    $rekapPegawai = [];

    foreach ($semuaAbsensi as $absen) {
        $nama = $absen->pegawai->name;
        $tanggal = \Carbon\Carbon::parse($absen->attendance_time)->format('d-m-Y');

        if (!isset($rekapPegawai[$nama])) {
            $rekapPegawai[$nama] = [
                'nama' => $nama,
                'hadir' => 0,
                'izin' => 0,
                'tidak_hadir' => 0,
                'tanggal' => [
                    'Hadir' => [],
                    'Izin' => [],
                    'Tidak Hadir' => [],
                ]
            ];
        }

        switch ($absen->status) {
            case 'Hadir':
                $rekapPegawai[$nama]['hadir']++;
                $rekapPegawai[$nama]['tanggal']['Hadir'][] = $tanggal;
                break;
            case 'Izin':
                $rekapPegawai[$nama]['izin']++;
                $rekapPegawai[$nama]['tanggal']['Izin'][] = $tanggal;
                break;
            case 'Tidak Hadir':
                $rekapPegawai[$nama]['tidak_hadir']++;
                $rekapPegawai[$nama]['tanggal']['Tidak Hadir'][] = $tanggal;
                break;
        }
    }

    $absensis = $query->paginate(10)->withQueryString();
    $rekapPegawai = array_values($rekapPegawai);
    $pegawaiList = \App\Models\Pegawai::all();

    return view('riwayat-absensi', compact('absensis', 'rekapPegawai', 'pegawaiList'));
}
 public function downloadPDF(Request $request)
{
    $query = Absensi::with('pegawai');

    if ($request->filled('nama')) {
        $query->whereHas('pegawai', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->nama . '%');
        });
    }

    $bulan = $request->input('bulan', \Carbon\Carbon::now()->format('Y-m'));
        $tanggal = \Carbon\Carbon::parse($bulan);
        $query->whereMonth('attendance_time', $tanggal->month)
              ->whereYear('attendance_time', $tanggal->year);

    $absensis = $query->orderBy('attendance_time', 'desc')->get();

    $pdf = Pdf::loadView('riwayat_pdf', [
        'absensis' => $absensis,
        'nama' => $request->nama,
          'bulan' => $bulan,
    ]);

    return $pdf->download('Riwayat_Absensi.pdf');
    }
}