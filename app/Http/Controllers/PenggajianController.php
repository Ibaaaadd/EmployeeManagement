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
    public function updateGajiBulan($pegawaiId, \Carbon\Carbon $date)
    {
        $bulanTahun = $date->format('Y-m');
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();

        $pegawai = Pegawai::findOrFail($pegawaiId);

        // Cek setting libur untuk bulan ini
        $settingLibur = \App\Models\SettingLibur::where('bulan', $bulanTahun)->first();
        $liburDates = [];
        if ($settingLibur && !empty($settingLibur->tanggal_merah)) {
            $tanggals = explode(',', $settingLibur->tanggal_merah);
            foreach ($tanggals as $t) {
                $t = trim($t);
                if (is_numeric($t) && $t > 0 && $t <= 31) {
                    $liburDates[] = sprintf('%s-%02d', $bulanTahun, $t);
                }
            }
        }

        $attendances = Absensi::where('pegawai_id', $pegawai->id)
            ->whereDate('attendance_time', '>=', $startDate)
            ->whereDate('attendance_time', '<=', $endDate)
            ->get();

        $jumlahHadir = 0;
        $jumlahIzin = 0;
        $jumlahTidakHadir = 0;
        $jumlahTerlambat = 0;
        $totalHariKerja = 0;

        // Hitung total hari kerja (Senin - Jumat) sebulan penuh
        $tempDate = $startDate->copy();
        while ($tempDate <= $endDate) {
            if ($tempDate->isWeekday()) {
                $totalHariKerja++;
            }
            $tempDate->addDay();
        }

        $gajiPokok = $pegawai->gaji;
        $gajiPerHari = $totalHariKerja > 0 ? floor($gajiPokok / $totalHariKerja) : 0;

        $currentDate = $startDate->copy();
        $today = \Carbon\Carbon::today();
        $now = \Carbon\Carbon::now();
        $cutoffTime = \Carbon\Carbon::today()->setTime(17, 0, 0); // Jam 5 sore
        
        // Evaluasi sampai dengan hari ini (termasuk hari ini)
        $evalEndDate = $endDate->gt($today) ? $today : $endDate;

        while ($currentDate <= $evalEndDate) {
            // Evaluasi di hari kerja yang bukan tanggal merah
            if ($currentDate->isWeekday()) {
                $dateStr = $currentDate->format('Y-m-d');
                if (!in_array($dateStr, $liburDates)) {
                    $att = $attendances->first(function ($item) use ($dateStr) {
                        return \Carbon\Carbon::parse($item->attendance_time)->format('Y-m-d') === $dateStr;
                    });

                    if ($att) {
                        if ($att->status == 'Izin') {
                            $jumlahIzin++;
                        } elseif ($att->status == 'Tidak Hadir') {
                            $jumlahTidakHadir++;
                        } elseif ($att->status == 'Hadir') {
                            $jumlahHadir++;
                            if ($att->is_late) {
                                $jumlahTerlambat++;
                            }
                        }
                    } else {
                        // Jika tidak ada data absen
                        if ($currentDate->lt($today)) {
                            // Hari sudah lewat, otomatis alpha
                            $jumlahTidakHadir++;
                        } elseif ($currentDate->isSameDay($today)) {
                            // Hari ini: cek apakah sudah lewat jam 5 sore
                            if ($now->gte($cutoffTime)) {
                                // Sudah lewat jam 5 sore, belum absen = auto alpha
                                $jumlahTidakHadir++;
                            }
                        }
                    }
                }
            }
            $currentDate->addDay();
        }

        // Sistem Akumulatif: gaji dihitung dari jumlah hari hadir
        $totalGajiHadir = $jumlahHadir * $gajiPerHari;
        $potonganTelat = $jumlahTerlambat * 30000;
        $totalGaji = $totalGajiHadir - $potonganTelat;

        // Update or Create di RiwayatGaji
        RiwayatGaji::updateOrCreate(
            [
                'pegawai_id' => $pegawai->id,
                'tanggal' => $startDate->format('Y-m-d'), // Kita jadikan awal bulan sebagai identifier untuk bulan tersebut
            ],
            [
                'periode' => '1 Bulan Full',
                'gaji_pokok' => $gajiPokok,
                'insentif' => 0, // default, atau bisa disesuaikan nanti
                'potongan' => $potonganTelat,
                'total_gaji' => $totalGaji,
                'jumlah_hadir' => $jumlahHadir,
                'jumlah_izin' => $jumlahIzin,
                'jumlah_tidak_hadir' => $jumlahTidakHadir,
                'jumlah_terlambat' => $jumlahTerlambat,
                'total_hari_kerja' => $totalHariKerja,
                'gaji_per_hari' => $gajiPerHari,
                'tanggal_merah' => $settingLibur ? $settingLibur->tanggal_merah : null,
            ]
        );
    }

    public function index()
    {
        // Daftar semua karyawan beserta riwayat gajinya
        $pegawais = Pegawai::with('riwayat_gajis')->get();
        return view('penggajian', compact('pegawais'));
    }

    // New method for unified salary history page
    public function historiGajiIndex(Request $request)
    {
        $query = Pegawai::with(['riwayat_gajis' => function($q) {
            $q->orderBy('tanggal', 'desc');
        }]);

        if ($request->filled('employee_id')) {
            $query->where('id', $request->employee_id);
        }

        $pegawais = $query->orderBy('name', 'asc')->paginate(10);
        $pegawaiList = Pegawai::orderBy('name', 'asc')->get();
        
        return view('histori-gaji', compact('pegawais', 'pegawaiList'));
    }

    // New method for detailed salary history per employee
    public function historiGajiDetail($id)
    {
        $pegawai = Pegawai::with(['riwayat_gajis' => function($q) {
            $q->orderBy('tanggal', 'desc');
        }])->findOrFail($id);

        return view('histori-gaji-detail', compact('pegawai'));
    }

    public function show($id)
    {
        $pegawai = Pegawai::with(['riwayat_gajis' => function($q) {
            $q->orderBy('tanggal', 'desc');
        }])->findOrFail($id);

        return view('penggajian_detail', compact('pegawai'));
    }

    public function riwayat(Request $request)
    {
        $query = RiwayatGaji::with('pegawai');

        if ($request->filled('nama')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }
        
        $bulan = $request->input('bulan', \Carbon\Carbon::now()->format('Y-m'));
        $tanggal = \Carbon\Carbon::parse($bulan);
        $query->whereMonth('tanggal', $tanggal->month)
              ->whereYear('tanggal', $tanggal->year);

        $riwayat = $query->orderBy('created_at', 'desc')->get();
        $pegawaiList = \App\Models\Pegawai::all();

        return view('riwayat', compact('riwayat', 'pegawaiList'));
    }

    public function destroyRiwayat($id)
    {
        $riwayat = RiwayatGaji::findOrFail($id);

        // Hapus file pdf slip jika ada
        if ($riwayat->pdf_path) {
            $storagePath = str_replace('storage/', 'public/', $riwayat->pdf_path);
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
        }

        $riwayat->delete();

        return redirect()->back()->with('success', 'Data riwayat gaji berhasil dihapus.');
    }

    public function generateSlipGaji($id)
    {
        $riwayat = \App\Models\RiwayatGaji::with('pegawai')->findOrFail($id);

        // Calculate breakdown potongan
        $potonganIzin = $riwayat->jumlah_izin * ($riwayat->gaji_per_hari ?? 0);
        $potonganAlpha = $riwayat->jumlah_tidak_hadir * ($riwayat->gaji_per_hari ?? 0);
        $potonganTelat = $riwayat->potongan - ($potonganIzin + $potonganAlpha);
        $jumlahTerlambat = $potonganTelat > 0 ? ($potonganTelat / 30000) : 0;

        $viewData = [
            'pegawai' => $riwayat->pegawai,
            'periode' => $riwayat->periode,
            'bulanTahun' => $riwayat->tanggal,
            'gajiPokok' => $riwayat->gaji_pokok,
            'insentif' => $riwayat->insentif,
            'jumlahIzin' => $riwayat->jumlah_izin ?? 0,
            'jumlahTidakHadir' => $riwayat->jumlah_tidak_hadir ?? 0,
            'jumlahTerlambat' => $jumlahTerlambat,
            'gajiPerHari' => $riwayat->gaji_per_hari ?? 30000,
            'potonganIzin' => $potonganIzin,
            'potonganAlpha' => $potonganAlpha,
            'potonganTelat' => $potonganTelat,
            'totalPengurangan' => $riwayat->potongan ?? 0,
            'totalGaji' => $riwayat->total_gaji,
            'totalHariKerja' => $riwayat->total_hari_kerja ?? 0,
        ];
        $pdf = Pdf::loadView('slip_pdf', $viewData);

        return $pdf->download('SlipGaji_' . $riwayat->pegawai->name . '_' . date('F_Y', strtotime($riwayat->tanggal)) . '.pdf');
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