<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * @method void middleware(\Closure|string|array $middleware)
 */
class PegawaiController extends Controller
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

    public function index()
    {
        return view('pegawai'); 
    }

   public function store(Request $request)
   {

        $request->merge([
        'gaji' => str_replace('.', '', $request->gaji),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'nomor' => 'required|string|max:15',
            'jabatan' => 'required|string|max:100',
            'gaji' => 'required|integer',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Simpan pegawai
        $pegawai = Pegawai::create([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'nomor' => $request->nomor,
            'jabatan' => $request->jabatan,
            'gaji' => $request->gaji,
        ]);

        // Simpan user dengan role 'user' (pegawai biasa)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pegawai_id' => $pegawai->id,
            'role' => 'user', // Role user untuk pegawai biasa
        ]);

        return redirect('/pegawai')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'nomor' => 'required|string|max:15',
            'jabatan' => 'required|string|max:255',
            'gaji' => 'required|numeric|min:0',
        ]);

        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update($validated);

        return redirect()->route('pegawai.showAll')->with('success', 'Pegawai berhasil diupdate!');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawai.showAll')->with('success', 'Data pegawai berhasil dihapus.');;
    }
    public function showAll()
    {
        $pegawaiList = Pegawai::paginate(10);
        return view('daftarpegawai', compact('pegawaiList'));
    }
}
