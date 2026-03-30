@extends('layouts.app')

@section('title', 'Detail Histori Gaji')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Histori Gaji: {{ $pegawai->name }}
            </h2>
            <a href="{{ route('histori-gaji.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <!-- Informasi Pegawai -->
        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp mb-4" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-user fs-4 text-primary"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted small text-uppercase fw-bold">Nama Pegawai</p>
                                <h6 class="mb-0 fw-bold">{{ $pegawai->name }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-id-badge fs-4 text-info"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted small text-uppercase fw-bold">Jabatan</p>
                                <h6 class="mb-0 fw-bold">{{ $pegawai->jabatan }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-money-bill-wave fs-4 text-success"></i>
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

        <!-- Tabel Histori Gaji Per Bulan -->
        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fa-solid fa-clock-rotate-left text-info me-2"></i> Histori Gaji Per Bulan
                </h5>
            </div>
            <div class="card-body p-4">
                @if ($pegawai->riwayat_gajis->isEmpty())
                    <div class="alert alert-warning text-center rounded-3 border-0 py-4">
                        <i class="fa-solid fa-circle-exclamation fs-1 text-warning mb-3 d-block"></i>
                        Belum ada data gaji untuk pegawai ini.
                    </div>
                @else
                    <!-- Card Grid Layout -->
                    <div class="row g-4">
                        @foreach ($pegawai->riwayat_gajis->sortByDesc('tanggal') as $index => $riwayat)
                            @php
                                $totalHadir = $riwayat->total_hari_kerja - ($riwayat->jumlah_izin + $riwayat->jumlah_tidak_hadir);
                                if($totalHadir < 0) $totalHadir = 0;
                                
                                // Calculate late days
                                $potonganAbsen = ($riwayat->jumlah_izin + $riwayat->jumlah_tidak_hadir) * $riwayat->gaji_per_hari;
                                $potonganTelat = $riwayat->potongan - $potonganAbsen;
                                $jumlahTerlambat = $potonganTelat > 0 ? ($potonganTelat / 30000) : 0;
                            @endphp
                            
                            <div class="col-12">
                                <div class="card border-0 shadow-sm hover-lift" style="border-radius: 20px; transition: all 0.3s ease;">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <!-- Periode & Nomor -->
                                            <div class="col-lg-2 col-md-3 mb-3 mb-md-0">
                                                <div class="text-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                                         style="width: 45px; height: 45px;">
                                                        <span class="fw-bold text-primary fs-5">{{ $index + 1 }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-info text-dark bg-opacity-10 py-2 px-3 rounded-pill border border-info">
                                                            <i class="fa-regular fa-calendar me-1"></i>
                                                            {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Stats Grid -->
                                            <div class="col-lg-7 col-md-6 mb-3 mb-md-0">
                                                <div class="row g-3">
                                                    <!-- Total Hari Kerja -->
                                                    <div class="col-6 col-sm-4">
                                                        <div class="text-center p-2 bg-light rounded-3">
                                                            <small class="text-muted d-block mb-1">Total Hari Kerja</small>
                                                            <span class="badge bg-secondary fs-6">{{ $riwayat->total_hari_kerja }} hari</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Hadir -->
                                                    <div class="col-6 col-sm-4">
                                                        <div class="text-center p-2 bg-light rounded-3">
                                                            <small class="text-muted d-block mb-1">Hadir</small>
                                                            <span class="badge bg-success fs-6">{{ $totalHadir }} hari</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Izin -->
                                                    <div class="col-6 col-sm-4">
                                                        <div class="text-center p-2 bg-light rounded-3">
                                                            <small class="text-muted d-block mb-1">Izin</small>
                                                            @if($riwayat->jumlah_izin > 0)
                                                                <span class="badge bg-warning text-dark fs-6">{{ $riwayat->jumlah_izin }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Alpha -->
                                                    <div class="col-6 col-sm-4">
                                                        <div class="text-center p-2 bg-light rounded-3">
                                                            <small class="text-muted d-block mb-1">Alpha</small>
                                                            @if($riwayat->jumlah_tidak_hadir > 0)
                                                                <span class="badge bg-danger fs-6">{{ $riwayat->jumlah_tidak_hadir }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Terlambat -->
                                                    <div class="col-6 col-sm-4">
                                                        <div class="text-center p-2 bg-light rounded-3">
                                                            <small class="text-muted d-block mb-1">Terlambat</small>
                                                            @if($jumlahTerlambat > 0)
                                                                <span class="badge bg-warning text-dark fs-6">{{ $jumlahTerlambat }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Tanggal Merah -->
                                                    <div class="col-12 col-sm-4">
                                                        <div class="text-center p-2 bg-light rounded-3">
                                                            <small class="text-muted d-block mb-1">Tanggal Merah</small>
                                                            @if($riwayat->tanggal_merah)
                                                                @php
                                                                    $dates = explode(',', $riwayat->tanggal_merah);
                                                                @endphp
                                                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                                    @foreach($dates as $date)
                                                                        <span class="badge bg-danger rounded-pill">{{ trim($date) }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted small">-</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gaji & Action -->
                                            <div class="col-lg-3 col-md-3">
                                                <div class="text-center">
                                                    <!-- Potongan -->
                                                    @if($riwayat->potongan > 0)
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">Potongan</small>
                                                            <span class="text-danger fw-bold">
                                                                - Rp {{ number_format($riwayat->potongan, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Total Gaji -->
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block">Total Gaji</small>
                                                        <h4 class="text-success fw-bold mb-0">
                                                            Rp {{ number_format($riwayat->total_gaji, 0, ',', '.') }}
                                                        </h4>
                                                    </div>
                                                    
                                                    <!-- Download Button -->
                                                    <a href="{{ route('slipgaji.pdf', $riwayat->id) }}" 
                                                       class="btn btn-danger rounded-pill px-4 shadow-sm w-100">
                                                        <i class="fa-solid fa-file-pdf me-2"></i>Slip PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary Card -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-light border">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="p-3">
                                            <i class="fas fa-calendar-check fs-3 text-primary mb-2 d-block"></i>
                                            <h4 class="mb-0 fw-bold">{{ $pegawai->riwayat_gajis->count() }}</h4>
                                            <small class="text-muted">Bulan Tercatat</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3">
                                            <i class="fas fa-money-bill-wave fs-3 text-success mb-2 d-block"></i>
                                            <h4 class="mb-0 fw-bold">Rp {{ number_format($pegawai->riwayat_gajis->sum('total_gaji'), 0, ',', '.') }}</h4>
                                            <small class="text-muted">Total Gaji Diterima</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3">
                                            <i class="fas fa-scissors fs-3 text-danger mb-2 d-block"></i>
                                            <h4 class="mb-0 fw-bold">Rp {{ number_format($pegawai->riwayat_gajis->sum('potongan'), 0, ',', '.') }}</h4>
                                            <small class="text-muted">Total Potongan</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3">
                                            <i class="fas fa-chart-line fs-3 text-info mb-2 d-block"></i>
                                            <h4 class="mb-0 fw-bold">
                                                @php
                                                    $avgGaji = $pegawai->riwayat_gajis->count() > 0 ? 
                                                               $pegawai->riwayat_gajis->avg('total_gaji') : 0;
                                                @endphp
                                                Rp {{ number_format($avgGaji, 0, ',', '.') }}
                                            </h4>
                                            <small class="text-muted">Rata-rata / Bulan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection