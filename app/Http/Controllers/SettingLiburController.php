<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingLibur;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * @method void middleware(\Closure|string|array $middleware)
 */
class SettingLiburController extends Controller
{
    public function __construct()
    {
        // Hanya admin yang bisa akses controller ini
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
            }
            return $next($request);
        });
    }

    // API methods for existing functionality
    public function getLibur(Request $request)
    {
        $bulan = $request->query('bulan');
        $setting = SettingLibur::where('bulan', $bulan)->first();

        return response()->json([
            'tanggal_merah' => $setting ? $setting->tanggal_merah : ''
        ]);
    }

    public function saveLibur(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'tanggal_merah' => 'nullable|string'
        ]);

        SettingLibur::updateOrCreate(
            ['bulan' => $request->bulan],
            ['tanggal_merah' => $request->tanggal_merah]
        );

        return response()->json(['status' => 'success', 'message' => 'Tanggal merah bulan ini berhasil disimpan.']);
    }

    // Web interface methods
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Get all holiday settings for current year
        $holidays = SettingLibur::where('bulan', 'like', $currentYear . '%')
            ->orderBy('bulan', 'asc')
            ->get();

        return view('setting-libur', compact('holidays', 'currentYear', 'currentMonth'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|string|size:7', // Format: Y-m
            'tanggal_merah' => 'nullable|string',
        ]);

        // Clean and validate tanggal_merah format
        $tanggalMerah = $request->tanggal_merah;
        if ($tanggalMerah) {
            // Remove spaces and ensure format is comma-separated numbers
            $tanggalMerah = preg_replace('/\s+/', '', $tanggalMerah);

            // Validate each date
            $dates = explode(',', $tanggalMerah);
            foreach ($dates as $date) {
                if (!is_numeric($date) || $date < 1 || $date > 31) {
                    return redirect()->back()
                        ->withErrors(['tanggal_merah' => 'Format tanggal merah tidak valid. Gunakan angka 1-31 dipisahkan koma. Contoh: 1,17,25'])
                        ->withInput();
                }
            }
        }

        SettingLibur::updateOrCreate(
            ['bulan' => $request->bulan],
            ['tanggal_merah' => $tanggalMerah]
        );

        return redirect()->back()->with('success', 'Tanggal merah berhasil disimpan!');
    }

    public function destroy($bulan)
    {
        SettingLibur::where('bulan', $bulan)->delete();
        return redirect()->back()->with('success', 'Tanggal merah berhasil dihapus!');
    }
}
