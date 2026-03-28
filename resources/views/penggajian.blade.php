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
                <div class="table-responsive">
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
                            @forelse ($pegawais as $index => $pegawai)
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
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pegawai.</td>
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

