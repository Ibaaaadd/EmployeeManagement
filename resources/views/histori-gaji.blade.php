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

        <div class="alert alert-info border-0 shadow-sm animate__animated animate__fadeIn" style="border-radius: 15px;">
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

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">Nama Pegawai</th>
                                <th width="20%">Jabatan</th>
                                <th width="20%">Gaji Bulan Ini</th>
                                <th width="25%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pegawais as $index => $pegawai)
                                @php
                                    // Get current month's salary
                                    $currentMonth = \Carbon\Carbon::now()->format('Y-m-d');
                                    $currentMonthStart = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                                    $gajiThisMonth = $pegawai->riwayat_gajis->where('tanggal', $currentMonthStart)->first();
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
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
                                                <div class="text-muted small">
                                                    Hadir: {{ $totalHadir }} hari
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
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-circle-exclamation fs-2 d-block mb-2"></i>
                                        Belum ada data pegawai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
