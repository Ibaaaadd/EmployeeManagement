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

        @auth
            @if(Auth::user()->role === 'admin')
                {{-- Filter hanya untuk admin --}}
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
            @else
                {{-- Info untuk user biasa --}}
                <div class="alert alert-info border-0 shadow-sm animate__animated animate__fadeIn mb-4" style="border-radius: 15px;">
                    <i class="fa-solid fa-info-circle me-2"></i> Anda melihat histori gaji untuk: <strong>{{ Auth::user()->name }}</strong>
                </div>
            @endif
        @endauth

        @auth
            @if(Auth::user()->role === 'admin')
                {{-- Admin melihat semua pegawai --}}
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
                        <div class="desktop-view-table">
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
                </div>
                
                {{-- Mobile Card View --}}
                <div class="mobile-view-card">
                    @foreach ($pegawais as $index => $pegawai)
                        @php
                            $currentMonthStart = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                            $gajiThisMonth = $pegawai->riwayat_gajis->where('tanggal', $currentMonthStart)->first();
                        @endphp
                        <div class="histori-card mb-3">
                            <div class="card-header-mobile">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle-histori">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">{{ $pegawai->name }}</h6>
                                            <span class="badge bg-secondary text-white rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                {{ $pegawai->jabatan }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary text-white rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        {{ $pegawais->firstItem() + $index }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body-mobile">
                                @if($gajiThisMonth)
                                    @php
                                        $totalHadir = $gajiThisMonth->total_hari_kerja - ($gajiThisMonth->jumlah_izin + $gajiThisMonth->jumlah_tidak_hadir);
                                        if($totalHadir < 0) $totalHadir = 0;
                                    @endphp
                                    <div class="salary-card">
                                        <div class="salary-header">
                                            <span class="salary-label">
                                                <i class="fa-solid fa-wallet me-1"></i>Gaji Bulan Ini
                                            </span>
                                            <span class="salary-amount">
                                                Rp {{ number_format($gajiThisMonth->total_gaji, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="attendance-info">
                                            <i class="fa-solid fa-calendar-check me-1"></i>
                                            <span>Hadir: <strong>{{ $totalHadir }} hari</strong></span>
                                        </div>
                                    </div>
                                @else
                                    <div class="no-data-card">
                                        <i class="fa-solid fa-circle-info me-2"></i>
                                        <span>Belum ada data gaji bulan ini</span>
                                    </div>
                                @endif
                                
                                <a href="{{ route('histori-gaji.detail', $pegawai->id) }}" class="btn btn-primary w-100 rounded-pill mt-3">
                                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Lihat Histori Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endslot

            @if(!$pegawais->isEmpty())
                @slot('pagination')
                    <div class="modern-pagination">
                        {{ $pegawais->withQueryString()->links() }}
                    </div>
                @endslot
            @endif
        @endcomponent
            @else
                {{-- User biasa: Langsung redirect ke detail atau tampilkan card summary --}}
                @php
                    // Get user's own salary data
                    $userPegawai = $pegawais->first();
                    if($userPegawai) {
                        $currentMonthStart = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                        $gajiThisMonth = $userPegawai->riwayat_gajis->where('tanggal', $currentMonthStart)->first();
                    }
                @endphp
                
                @if($userPegawai)
                    <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
                        <div class="card-header bg-white py-3 px-4" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                            <h6 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-wallet text-success me-2"></i> Gaji Anda Bulan Ini</h6>
                        </div>
                        <div class="card-body p-4">
                            @if($gajiThisMonth)
                                @php
                                    $totalHadir = $gajiThisMonth->total_hari_kerja - ($gajiThisMonth->jumlah_izin + $gajiThisMonth->jumlah_tidak_hadir);
                                    if($totalHadir < 0) $totalHadir = 0;
                                @endphp
                                <div class="user-salary-card">
                                    <div class="salary-main">
                                        <div class="salary-icon">
                                            <i class="fa-solid fa-money-bill-wave"></i>
                                        </div>
                                        <div class="salary-info">
                                            <span class="salary-label">Total Gaji Bulan Ini</span>
                                            <span class="salary-value">Rp {{ number_format($gajiThisMonth->total_gaji, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="attendance-summary">
                                        <div class="summary-item">
                                            <i class="fa-solid fa-calendar-check text-success"></i>
                                            <span><strong>{{ $totalHadir }}</strong> Hari Hadir</span>
                                        </div>
                                        <div class="summary-item">
                                            <i class="fa-solid fa-person-circle-question text-warning"></i>
                                            <span><strong>{{ $gajiThisMonth->jumlah_izin }}</strong> Izin</span>
                                        </div>
                                        <div class="summary-item">
                                            <i class="fa-solid fa-person-circle-xmark text-danger"></i>
                                            <span><strong>{{ $gajiThisMonth->jumlah_tidak_hadir }}</strong> Alpha</span>
                                        </div>
                                    </div>
                                    
                                    @if($gajiThisMonth->potongan > 0)
                                        <div class="deduction-summary">
                                            <span class="deduction-label">Total Potongan</span>
                                            <span class="deduction-value">- Rp {{ number_format($gajiThisMonth->potongan, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info text-center rounded-3 border-0">
                                    <i class="fa-solid fa-info-circle fs-2 mb-2"></i>
                                    <p class="mb-0">Belum ada data gaji untuk bulan ini.</p>
                                </div>
                            @endif
                            
                            <div class="mt-4">
                                <a href="{{ route('histori-gaji.detail', $userPegawai->id) }}" class="btn btn-primary w-100 rounded-pill">
                                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Lihat Histori Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning text-center rounded-3 border-0">
                        <i class="fa-solid fa-exclamation-triangle fs-2 mb-2"></i>
                        <p class="mb-0">Data pegawai tidak ditemukan. Hubungi administrator.</p>
                    </div>
                @endif
            @endif
        @endauth
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

/* Histori Card Styles */
.histori-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.histori-card:hover {
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

.avatar-circle-histori {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.salary-card {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 16px;
    border-radius: 12px;
}

.salary-header {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 12px;
}

.salary-label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.salary-amount {
    color: white;
    font-size: 1.4rem;
    font-weight: 700;
}

.attendance-info {
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 12px;
    border-radius: 8px;
    color: white;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

.no-data-card {
    background: #f8fafc;
    padding: 16px;
    border-radius: 12px;
    text-align: center;
    color: #64748b;
    font-size: 0.9rem;
}

/* Desktop/Mobile Toggle */
.desktop-view-table {
    display: table-row-group;
}

.mobile-view-card {
    display: none;
}

/* Modern Pagination */
.modern-pagination nav {
    display: flex;
    justify-content: center;
}

.modern-pagination .pagination {
    gap: 6px;
}

.modern-pagination .page-link {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    color: #64748b;
    font-weight: 600;
    padding: 8px 14px;
    transition: all 0.3s ease;
}

.modern-pagination .page-link:hover {
    background: var(--accent-color);
    color: white;
    border-color: var(--accent-color);
}

.modern-pagination .page-item.active .page-link {
    background: var(--accent-color);
    border-color: var(--accent-color);
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
}

/* Responsive Design */
@media (max-width: 767.98px) {
    .desktop-view-table {
        display: none !important;
    }
    
    .mobile-view-card {
        display: block !important;
    }
}

/* User Salary Card */
.user-salary-card {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 16px;
    padding: 24px;
    color: white;
    margin-bottom: 16px;
}

.salary-main {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.salary-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.salary-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.salary-label {
    font-size: 0.85rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.salary-value {
    font-size: 1.8rem;
    font-weight: 700;
}

.attendance-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.summary-item {
    background: rgba(255, 255, 255, 0.15);
    padding: 12px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    text-align: center;
}

.summary-item i {
    font-size: 1.5rem;
}

.summary-item span {
    font-size: 0.85rem;
}

.deduction-summary {
    background: rgba(255, 255, 255, 0.15);
    padding: 12px 16px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.deduction-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.deduction-value {
    font-size: 1.1rem;
    font-weight: 700;
}

@media (max-width: 767.98px) {
    .salary-value {
        font-size: 1.5rem;
    }
    
    .attendance-summary {
        grid-template-columns: 1fr;
    }
    
    .summary-item {
        flex-direction: row;
        justify-content: flex-start;
        gap: 12px;
    }
    
    .summary-item i {
        font-size: 1.3rem;
    }
}
</style>
@endsection
