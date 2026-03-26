<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingLibur;

class SettingLiburController extends Controller
{
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
}
