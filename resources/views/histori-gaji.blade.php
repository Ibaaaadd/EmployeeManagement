@extends('layouts.app')

@section('title', 'Histori Gaji Pegawai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Histori Gaji Pegawai
            </h2>
        </div>

        <div class="alert alert-info border-0 shadow-sm animate__animated animate__fadeIn mb-4" style="border-radius: 15px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fs-3 me-3"></i>
                <div>
                    <strong>Informasi Perhitungan Gaji:</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        <li>Gaji dihitung otomatis setiap kali pegawai submit absensi</li>
                        <li>Weekend (Sabtu-Minggu) tidak dihitung sebagai hari kerja</li>
                        <li>Tanggal merah (libur nasional) tetap dihitung sebagai hari kerja aktif - pegawai tidak perlu absen</li>
                        <li>Potongan hanya berlaku untuk hari kerja biasa (tidak termasuk tanggal merah & weekend)</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm animate__animated animate__fadeIn mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-filter me-2"></i>Filter Pegawai</h6>
                <form action="{{ route('histori-gaji.index') }}" method="GET">
                    <div class="row align-items-end g-3">
                        <div class="col-md-5">
                            <label class="form-label small text-muted">Pilih Pegawai</label>
                            <select class="form-select select-search" name="employee_id">
                                <option value="">-- Semua Pegawai --</option>
                                @foreach($pegawaiList as $p)
                                    <option value="{{ $p->id }}" {{ request('employee_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">
                                <i class="fa-solid fa-magnifying-glass me-2"></i>Cari
                            </button>
                        </div>
                        @if(request()->filled('employee_id'))
                        <div class="col-md-2">
                            <a href="{{ route('histori-gaji.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                                <i class="fa-solid fa-rotate-left me-2"></i>Reset
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @component('components.datatable', [
            'id' => 'tabelHistoriGaji',
            'title' => 'Daftar Pegawai & Gaji Bulan Ini',
            'icon' => 'fa-solid fa-money-bill-wave',
            'empty' => $pegawais->isEmpty()
        ])
            @slot('head')
                <th width="5%" class="text-center">#</th>
                <th width="30%">Nama Pegawai</th>
                <th width="20%">Jabatan</th>
                <th width="20%">Gaji Bulan Ini</th>
                <th width="25%" class="text-center">Aksi</th>
            @endslot

            @slot('body')
                @foreach ($pegawais as $index => $pegawai)
                    @php
                        // Get current month's salary
                        $currentMonthStart = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                        $gajiThisMonth = $pegawai->riwayat_gajis->where('tanggal', $currentMonthStart)->first();
                    @endphp
                    <tr>
                        <td class="text-center">{{ $pegawais->firstItem() + $index }}</td>
                        <td class="fw-bold text-dark">
                            <i class="fa-solid fa-user-circle text-primary me-2"></i>
                            {{ $pegawai->name }}
                        </td>
                        <td>
                            <span class="badge bg-secondary text-white rounded-pill px-3 py-2">
                                {{ $pegawai->jabatan }}
                            </span>
                        </td>
                        <td>
                            @if($gajiThisMonth)
                                <div>
                                    <span class="text-success fw-bold fs-6">
                                        Rp {{ number_format($gajiThisMonth->total_gaji, 0, ',', '.') }}
                                    </span>
                                    @php
                                        $totalHadir = $gajiThisMonth->total_hari_kerja - ($gajiThisMonth->jumlah_izin + $gajiThisMonth->jumlah_tidak_hadir);
                                        if($totalHadir < 0) $totalHadir = 0;
                                    @endphp
                                    <div class="text-muted small mt-1">
                                        <i class="fa-solid fa-calendar-check me-1"></i>Hadir: {{ $totalHadir }} hari
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">
                                    <i class="fa-solid fa-circle-info me-1"></i>Belum ada data
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('histori-gaji.detail', $pegawai->id) }}" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm">
                                <i class="fa-solid fa-clock-rotate-left me-2"></i>Histori Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endslot

            @if(!$pegawais->isEmpty())
                @slot('pagination')
                    {{ $pegawais->withQueryString()->links() }}
                @endslot
            @endif
        @endcomponent
    </div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(document.querySelector('.select-search')) {
            new TomSelect('.select-search', {
                create: false,
                sortField: { field: "text", direction: "asc" }
            });
        }
    });
</script>
<style>
.ts-control {
    border-radius: 10px;
    padding: 10px 15px;
    border: 1px solid #e2e8f0;
    min-height: 45px;
}
.ts-dropdown {
    z-index: 9999 !important; /* Tambahkan ini agar dropdown muncul di atas elemen lain */
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.ts-control.focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    border-color: var(--accent-color);
}
</style>
@endsection
