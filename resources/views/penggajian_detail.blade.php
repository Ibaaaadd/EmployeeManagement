@extends('layouts.app')

@section('title', 'Detail Gaji Pegawai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Detail Gaji: {{ $pegawai->name }}
            </h2>
            <a href="{{ route('gaji.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp mb-4" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-id-badge text-primary"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted small text-uppercase fw-bold">Jabatan</p>
                                <h6 class="mb-0 fw-bold">{{ $pegawai->jabatan }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-money-bill-wave text-success"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted small text-uppercase fw-bold">Gaji Pokok / Bulan</p>
                                <h6 class="mb-0 fw-bold text-success">Rp {{ number_format($pegawai->gaji, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fa-solid fa-clock-rotate-left text-info me-2"></i> Histori Gaji Bulanan
                </h5>
            </div>
            <div class="card-body p-4">
                @if ($pegawai->riwayat_gajis->isEmpty())
                    <div class="alert alert-warning text-center rounded-3 border-0 py-4">
                        <i class="fa-solid fa-circle-exclamation fs-1 text-warning mb-3 d-block"></i>
                        Belum ada data gaji untuk pegawai ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Periode</th>
                                    <th>Total Hadir</th>
                                    <th>Izin / Alpha</th>
                                    <th>Potongan</th>
                                    <th>Total Gaji Diterima</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pegawai->riwayat_gajis as $riwayat)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info text-dark bg-opacity-10 py-2 px-3 rounded-pill border border-info border-opacity-25">
                                                <i class="fa-regular fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('F Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $totalHadir = $riwayat->total_hari_kerja - ($riwayat->jumlah_izin + $riwayat->jumlah_tidak_hadir);
                                                if($totalHadir < 0) $totalHadir = 0;
                                            @endphp
                                            {{ $totalHadir }} Hari
                                        </td>
                                        <td>
                                            <span class="text-warning"><i class="fa-solid fa-person-circle-question me-1"></i>{{ $riwayat->jumlah_izin }}</span> / 
                                            <span class="text-danger"><i class="fa-solid fa-person-circle-xmark me-1"></i>{{ $riwayat->jumlah_tidak_hadir }}</span>
                                        </td>
                                        <td class="text-danger fw-bold">Rp {{ number_format($riwayat->potongan, 0, ',', '.') }}</td>
                                        <td class="text-success fw-bold fs-6">Rp {{ number_format($riwayat->total_gaji, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('slipgaji.pdf', $riwayat->id) }}" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm">
                                                    <i class="fa-solid fa-file-pdf me-1"></i> Slip PDF
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
