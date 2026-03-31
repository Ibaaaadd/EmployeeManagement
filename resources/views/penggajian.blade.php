@extends('layouts.app')

@section('title', 'Data Penggajian Pegawai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Data Penggajian Pegawai
            </h2>
        </div>

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-body p-4">
                @if($pegawais->isEmpty())
                    <div class="alert alert-warning text-center rounded-3 border-0">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> Belum ada data pegawai.
                    </div>
                @else
                    {{-- Desktop: Table View --}}
                    <div class="table-responsive desktop-view">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pegawai</th>
                                    <th>Jabatan</th>
                                    <th>Gaji Pokok / Bulan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pegawais as $index => $pegawai)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold text-dark">{{ $pegawai->name }}</td>
                                        <td>
                                            <span class="badge bg-secondary text-white rounded-pill px-3 py-2">
                                                {{ $pegawai->jabatan }}
                                            </span>
                                        </td>
                                        <td class="text-success fw-bold">Rp {{ number_format($pegawai->gaji, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('gaji.show', $pegawai->id) }}" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm">
                                                <i class="fa-solid fa-file-invoice-dollar me-2"></i>Lihat Rekap Gaji
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile: Card View --}}
                    <div class="mobile-view">
                        @foreach ($pegawais as $index => $pegawai)
                            <div class="modern-card-gaji mb-3">
                                <div class="card-header-mobile">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0">{{ $pegawai->name }}</h6>
                                                <small class="text-muted">{{ $pegawai->jabatan }}</small>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-2">
                                            #{{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body-mobile">
                                    <div class="salary-info">
                                        <span class="salary-label">Gaji Pokok</span>
                                        <span class="salary-amount">Rp {{ number_format($pegawai->gaji, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <a href="{{ route('gaji.show', $pegawai->id) }}" class="btn btn-primary w-100 rounded-pill mt-3">
                                        <i class="fa-solid fa-file-invoice-dollar me-2"></i>Lihat Rekap Gaji
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
/* Modern Card for Salary */
.modern-card-gaji {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.modern-card-gaji:hover {
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

.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.salary-info {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 16px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
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
    font-size: 1.5rem;
    font-weight: 700;
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
}
</style>
@endsection

