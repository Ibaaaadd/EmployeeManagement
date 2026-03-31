@extends('layouts.app')

@section('title', 'Riwayat Absensi Pegawai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--success-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Riwayat Absensi
            </h2>
        </div>

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp mb-4" style="border-radius: 20px;">
            <div class="card-header bg-white py-3 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-filter text-success fs-6"></i>
                </div>
                <h6 class="mb-0 text-dark fw-bold">Filter Data Absensi</h6>
            </div>
            
            <div class="card-body p-4">
                {{-- Filter Form --}}
                <form method="GET" action="{{ route('absensi.riwayat') }}" class="row g-3 align-items-end">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            {{-- Admin bisa filter by nama --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Nama Pegawai</label>
                                <select name="nama" class="form-select select-search">
                                    <option value="">-- Semua Pegawai --</option>
                                    @foreach($pegawaiList ?? [] as $p)
                                        <option value="{{ $p->name }}" {{ request('nama') == $p->name ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            {{-- User biasa tidak perlu dropdown, langsung nama sendiri --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Pegawai</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled readonly>
                                <small class="text-muted">Menampilkan riwayat absensi Anda</small>
                            </div>
                        @endif
                    @else
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Pegawai</label>
                            <input type="text" class="form-control" value="Silakan login" disabled readonly>
                        </div>
                    @endauth
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Bulan & Tahun</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-days text-muted"></i></span>
                            <input type="month" name="bulan" class="form-control border-start-0 ps-0 datepicker-month" value="{{ request('bulan', \Carbon\Carbon::now()->format('Y-m')) }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="submit" class="btn btn-success w-100 rounded-pill shadow-sm d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-body p-4">
                <ul class="nav nav-tabs nav-fill mb-4 border-bottom-0 pb-2" id="absensiTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold rounded-pill text-dark" id="tabel-tab" data-bs-toggle="tab" data-bs-target="#tabel" type="button" role="tab" style="border: 1px solid #e2e8f0; margin-right: 10px;">
                           <i class="fa-solid fa-table-list me-2"></i> Tabel Umum
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold rounded-pill text-dark" id="rekap-tab" data-bs-toggle="tab" data-bs-target="#rekap" type="button" role="tab" style="border: 1px solid #e2e8f0; margin-left: 10px;">
                           <i class="fa-solid fa-users-viewfinder me-2"></i> Rekap Per Pegawai
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="absensiTabContent">
                    {{-- Tab: Tabel Umum --}}
                    <div class="tab-pane fade show active" id="tabel" role="tabpanel" aria-labelledby="tabel-tab">
                        @if($absensis->isEmpty())
                            <div class="alert alert-warning text-center rounded-3 border-0">
                                <i class="fa-solid fa-circle-exclamation me-2"></i> Data riwayat absensi yang Anda input tidak tersedia.
                            </div>
                        @else
                            {{-- Desktop: Table View --}}
                            <div class="table-responsive mb-4 desktop-view">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Jam Masuk</th>
                                            <th>Jam Pulang</th>
                                            <th>Tanggal</th>
                                            <th>Nama Pegawai</th>
                                            <th>Status</th>
                                            <th>Bukti Foto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($absensis as $absensi)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-dark border rounded-pill">
                                                        <i class="fa-regular fa-clock me-1"></i> {{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-dark border rounded-pill">
                                                        <i class="fa-regular fa-clock me-1"></i> {{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '-' }}
                                                    </span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($absensi->attendance_time)->format('d-m-Y') }}</td>
                                                <td class="fw-medium text-dark">{{ $absensi->pegawai->name }}</td>
                                                <td>
                                                    @if($absensi->status === 'Hadir')
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2">
                                                            <i class="fa-solid fa-check-circle me-1"></i> Hadir
                                                            @if($absensi->is_late)
                                                                <span class="text-danger fw-bold ms-1">(Telat)</span>
                                                            @endif
                                                        </span>
                                                    @elseif($absensi->status === 'Izin')
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2"><i class="fa-solid fa-envelope-open-text me-1"></i> Izin</span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2"><i class="fa-solid fa-xmark-circle me-1"></i> Tidak Hadir</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($absensi->status === 'Hadir' && $absensi->attendance_photo)
                                                        <a href="{{ asset('storage/' . $absensi->attendance_photo) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                            <i class="fa-solid fa-image me-1"></i> Lihat
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile: Card View --}}
                            <div class="mobile-view">
                                @foreach ($absensis as $absensi)
                                    <div class="modern-card mb-3">
                                        <div class="card-header-mobile">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1">{{ $absensi->pegawai->name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fa-regular fa-calendar me-1"></i>
                                                        {{ \Carbon\Carbon::parse($absensi->attendance_time)->format('d M Y') }}
                                                    </small>
                                                </div>
                                                <div>
                                                    @if($absensi->status === 'Hadir')
                                                        <span class="badge bg-success text-white rounded-pill px-3 py-2">
                                                            <i class="fa-solid fa-check-circle me-1"></i> Hadir
                                                        </span>
                                                    @elseif($absensi->status === 'Izin')
                                                        <span class="badge bg-warning text-white rounded-pill px-3 py-2">
                                                            <i class="fa-solid fa-envelope-open-text me-1"></i> Izin
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger text-white rounded-pill px-3 py-2">
                                                            <i class="fa-solid fa-xmark-circle me-1"></i> Alpha
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($absensi->is_late && $absensi->status === 'Hadir')
                                                <div class="alert alert-danger alert-sm py-1 px-2 mb-2" style="font-size: 0.75rem;">
                                                    <i class="fa-solid fa-clock me-1"></i> Terlambat
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="card-body-mobile">
                                            <div class="info-row">
                                                <div class="info-item">
                                                    <span class="info-label">Jam Masuk</span>
                                                    <span class="info-value">
                                                        <i class="fa-solid fa-arrow-right-to-bracket text-success me-1"></i>
                                                        {{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}
                                                    </span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="info-label">Jam Pulang</span>
                                                    <span class="info-value">
                                                        <i class="fa-solid fa-arrow-right-from-bracket text-danger me-1"></i>
                                                        {{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($absensi->status === 'Hadir' && $absensi->attendance_photo)
                                                <div class="mt-3">
                                                    <a href="{{ asset('storage/' . $absensi->attendance_photo) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                                        <i class="fa-solid fa-image me-1"></i> Lihat Bukti Foto
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- Modern Pagination --}}
                            <div class="modern-pagination mt-4">
                                {{ $absensis->links() }}
                            </div>

                            <div class="text-center text-md-end mt-3">
                                <a href="{{ route('absensi.riwayat.download', request()->query()) }}" class="btn btn-danger rounded-pill shadow-sm px-4 w-100 w-md-auto">
                                    <i class="fa-solid fa-file-pdf me-2"></i> Download Rekap PDF
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Tab: Rekap Per Pegawai --}}
                    <div class="tab-pane fade" id="rekap" role="tabpanel" aria-labelledby="rekap-tab">
                        {{-- Desktop: Table View --}}
                        <div class="table-responsive desktop-view">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nama Pegawai</th>
                                        <th class="text-center text-success"><i class="fa-solid fa-check-circle me-1"></i> Hadir</th>
                                        <th class="text-center text-warning"><i class="fa-solid fa-envelope-open-text me-1"></i> Izin</th>
                                        <th class="text-center text-danger"><i class="fa-solid fa-xmark-circle me-1"></i> Tidak Hadir</th>
                                        <th class="text-center">Detail Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rekapPegawai as $pegawai)
                                        <tr>
                                            <td class="fw-medium text-dark">{{ $pegawai['nama'] }}</td>
                                            <td class="text-center fw-bold text-success">{{ $pegawai['hadir'] }}</td>
                                            <td class="text-center fw-bold text-warning">{{ $pegawai['izin'] }}</td>
                                            <td class="text-center fw-bold text-danger">{{ $pegawai['tidak_hadir'] }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#detail-{{ $loop->index }}">
                                                    <i class="fa-solid fa-eye me-1"></i> Lihat Data
                                                </button>
                                                
                                                <div class="collapse mt-3 text-start" id="detail-{{ $loop->index }}">
                                                    <div class="card card-body bg-light border-0 shadow-sm rounded-3 p-3">
                                                        <div class="row">
                                                            @foreach($pegawai['tanggal'] as $status => $tanggalList)
                                                                <div class="col-12 mb-2">
                                                                    <strong class="d-block mb-1 small text-muted text-uppercase">{{ $status }} :</strong>
                                                                    @if(count($tanggalList) > 0)
                                                                        <div class="d-flex flex-wrap gap-1">
                                                                            @foreach($tanggalList as $tgl)
                                                                                <span class="badge {{ $status == 'Hadir' ? 'bg-success' : ($status == 'Izin' ? 'bg-warning' : 'bg-danger') }} bg-opacity-10 text-dark border py-1 px-2">{{ $tgl }}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="text-muted small">-</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile: Card View --}}
                        <div class="mobile-view">
                            @foreach($rekapPegawai as $pegawai)
                                <div class="modern-card mb-3">
                                    <div class="card-header-mobile">
                                        <h6 class="fw-bold text-dark mb-2">{{ $pegawai['nama'] }}</h6>
                                    </div>
                                    
                                    <div class="card-body-mobile">
                                        <div class="recap-stats mb-3">
                                            <div class="stat-item">
                                                <div class="stat-icon bg-success">
                                                    <i class="fa-solid fa-check-circle"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-label">Hadir</span>
                                                    <span class="stat-value text-success">{{ $pegawai['hadir'] }}</span>
                                                </div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-icon bg-warning">
                                                    <i class="fa-solid fa-envelope-open-text"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-label">Izin</span>
                                                    <span class="stat-value text-warning">{{ $pegawai['izin'] }}</span>
                                                </div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-icon bg-danger">
                                                    <i class="fa-solid fa-xmark-circle"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-label">Alpha</span>
                                                    <span class="stat-value text-danger">{{ $pegawai['tidak_hadir'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="button" class="btn btn-outline-info btn-sm w-100 rounded-pill" data-bs-toggle="collapse" data-bs-target="#detail-mobile-{{ $loop->index }}">
                                            <i class="fa-solid fa-eye me-1"></i> Lihat Detail Tanggal
                                        </button>
                                        
                                        <div class="collapse mt-3" id="detail-mobile-{{ $loop->index }}">
                                            <div class="detail-dates">
                                                @foreach($pegawai['tanggal'] as $status => $tanggalList)
                                                    <div class="date-group mb-2">
                                                        <strong class="d-block mb-1 small text-muted text-uppercase">{{ $status }} :</strong>
                                                        @if(count($tanggalList) > 0)
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach($tanggalList as $tgl)
                                                                    <span class="badge {{ $status == 'Hadir' ? 'bg-success' : ($status == 'Izin' ? 'bg-warning' : 'bg-danger') }} text-white py-1 px-2">{{ $tgl }}</span>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styling that aligns nav-tabs with our customized layout */
.nav-tabs .nav-link {
    background-color: #f8fafc;
    color: #64748b !important;
    transition: all 0.3s ease;
}
.nav-tabs .nav-link.active {
    background-color: var(--success-color) !important;
    color: white !important;
    border-color: var(--success-color) !important;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
}
.nav-tabs .nav-link:hover:not(.active) {
    background-color: #f1f5f9;
}

/* Modern Card Styling */
.modern-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.modern-card:hover {
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

.info-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
}

.info-item {
    background: #f8fafc;
    padding: 12px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
}

/* Recap Stats */
.recap-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 12px 8px;
    background: #f8fafc;
    border-radius: 12px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
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

.stat-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.stat-label {
    font-size: 0.7rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
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
    background: var(--success-color);
    color: white;
    border-color: var(--success-color);
}

.modern-pagination .page-item.active .page-link {
    background: var(--success-color);
    border-color: var(--success-color);
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
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
    /* Hide desktop, show mobile */
    .desktop-view {
        display: none !important;
    }
    
    .mobile-view {
        display: block !important;
    }
    
    /* Page adjustments */
    h2 {
        font-size: 1.15rem;
    }
    
    .card-header,
    .card-body {
        padding: 1rem !important;
    }
    
    /* Tab navigation */
    #absensiTab {
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 8px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    
    #absensiTab::-webkit-scrollbar {
        display: none;
    }
    
    #absensiTab .nav-item {
        min-width: 170px;
        flex-shrink: 0;
    }
    
    #absensiTab .nav-link {
        margin: 0 !important;
        white-space: nowrap;
        font-size: 0.85rem;
    }
    
    /* Button adjustments */
    .text-center .btn,
    .text-md-end .btn {
        width: 100% !important;
    }
    
    /* Info row adjustments for very small screens */
    @media (max-width: 360px) {
        .info-row {
            grid-template-columns: 1fr;
        }
        
        .recap-stats {
            grid-template-columns: 1fr;
        }
    }
}

/* Tablet adjustments */
@media (min-width: 768px) and (max-width: 991.98px) {
    .modern-pagination .page-link {
        padding: 6px 12px;
        font-size: 0.9rem;
    }
}
</style>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<!-- Flatpickr for Datepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown dengan fitur search menggunakan Tom Select
        document.querySelectorAll('.select-search').forEach((el) => {
            new TomSelect(el, {
                create: false,
                sortField: { field: "text", direction: "asc" },
                dropdownParent: 'body'
            });
        });

        // Datepicker functionality - if user requested datepicker.
        // The current input uses month picking, so we apply month formatting or use native depending on requirements.
        // We'll leave the type='month' to rely on nice native mobile UI and Chrome tools but apply plugin styles if needed.
    });
</script>
<style>
.ts-control {
    border-radius: 10px;
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    background-color: #f8fafc;
    box-shadow: none;
}
.ts-control.focus {
    background-color: #fff;
    border-color: var(--success-color);
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
}
.input-group:focus-within .input-group-text, .input-group:focus-within .form-control {
    border-color: var(--success-color);
}
</style>
@endsection
