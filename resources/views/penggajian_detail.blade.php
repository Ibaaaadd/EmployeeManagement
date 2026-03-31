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
                    {{-- Desktop: Table View --}}
                    <div class="table-responsive desktop-view">
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

                    {{-- Mobile: Card View --}}
                    <div class="mobile-view">
                        @foreach ($pegawai->riwayat_gajis as $riwayat)
                            @php
                                $totalHadir = $riwayat->total_hari_kerja - ($riwayat->jumlah_izin + $riwayat->jumlah_tidak_hadir);
                                if($totalHadir < 0) $totalHadir = 0;
                            @endphp
                            <div class="salary-history-card mb-3">
                                <div class="card-header-mobile">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-info text-white py-2 px-3 rounded-pill">
                                            <i class="fa-regular fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('F Y') }}
                                        </span>
                                        <span class="total-salary">
                                            Rp {{ number_format($riwayat->total_gaji, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body-mobile">
                                    <div class="stats-grid">
                                        <div class="stat-box bg-success-subtle">
                                            <div class="stat-icon bg-success">
                                                <i class="fa-solid fa-calendar-check"></i>
                                            </div>
                                            <div class="stat-info">
                                                <span class="stat-label">Hadir</span>
                                                <span class="stat-number text-success">{{ $totalHadir }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="stat-box bg-warning-subtle">
                                            <div class="stat-icon bg-warning">
                                                <i class="fa-solid fa-person-circle-question"></i>
                                            </div>
                                            <div class="stat-info">
                                                <span class="stat-label">Izin</span>
                                                <span class="stat-number text-warning">{{ $riwayat->jumlah_izin }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="stat-box bg-danger-subtle">
                                            <div class="stat-icon bg-danger">
                                                <i class="fa-solid fa-person-circle-xmark"></i>
                                            </div>
                                            <div class="stat-info">
                                                <span class="stat-label">Alpha</span>
                                                <span class="stat-number text-danger">{{ $riwayat->jumlah_tidak_hadir }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="deduction-info">
                                        <span class="deduction-label">Potongan</span>
                                        <span class="deduction-amount">- Rp {{ number_format($riwayat->potongan, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <a href="{{ route('slipgaji.pdf', $riwayat->id) }}" class="btn btn-danger w-100 rounded-pill mt-3">
                                        <i class="fa-solid fa-file-pdf me-2"></i> Download Slip Gaji
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Salary History Card */
.salary-history-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.salary-history-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.card-header-mobile {
    padding: 16px;
    border-bottom: 1px solid #f1f5f9;
}

.card-body-mobile {
    padding: 16px;
}

.total-salary {
    font-size: 1.25rem;
    font-weight: 700;
    color: #10b981;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 16px;
}

.stat-box {
    padding: 12px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.bg-success-subtle {
    background: rgba(16, 185, 129, 0.1);
}

.bg-warning-subtle {
    background: rgba(245, 158, 11, 0.1);
}

.bg-danger-subtle {
    background: rgba(239, 68, 68, 0.1);
}

.stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.stat-icon.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-icon.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-icon.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.stat-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.stat-label {
    font-size: 0.65rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.stat-number {
    font-size: 1.1rem;
    font-weight: 700;
}

.deduction-info {
    background: #fef2f2;
    border: 1px solid #fecaca;
    padding: 12px 16px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.deduction-label {
    font-size: 0.85rem;
    color: #991b1b;
    font-weight: 600;
}

.deduction-amount {
    font-size: 1rem;
    font-weight: 700;
    color: #dc2626;
}

/* Desktop/Mobile Toggle */
.desktop-view {
    display: block;
}

.mobile-view {
    display: none;
}

/* Responsive Design */
@media (max-width: 767.98px) {
    .desktop-view {
        display: none !important;
    }
    
    .mobile-view {
        display: block !important;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .stat-box {
        flex-direction: row;
        justify-content: flex-start;
        gap: 12px;
    }
    
    .stat-info {
        align-items: flex-start;
        flex: 1;
    }
}

@media (min-width: 768px) and (max-width: 991.98px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 360px) {
    .total-salary {
        font-size: 1rem;
    }
}
</style>
@endsection
