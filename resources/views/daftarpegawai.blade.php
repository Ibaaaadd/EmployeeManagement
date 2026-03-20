@extends('layouts.app')

@section('title', 'Daftar Pegawai')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-11">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Daftar Pegawai
            </h2>
            <a href="{{ url('/pegawai') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-plus me-2"></i> Tambah Pegawai
            </a>
        </div>

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary">No</th>
                                <th class="py-3 text-secondary">Identitas Pegawai</th>
                                <th class="py-3 text-secondary">Jabatan</th>
                                <th class="py-3 text-secondary">Kontak</th>
                                <th class="py-3 text-secondary">Gaji Pokok</th>
                                <th class="text-center pe-4 py-3 text-secondary">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pegawaiList as $index => $pegawai)
                                <tr style="transition: all 0.2s;">
                                    <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center py-2">
                                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm flex-shrink-0" style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">
                                                {{ strtoupper(substr($pegawai->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $pegawai->name }}</h6>
                                                <small class="text-muted d-block mt-1" style="line-height:1.2;"><i class="fa-solid fa-location-dot me-1"></i>{{ $pegawai->alamat }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">{{ $pegawai->jabatan }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small fw-medium"><i class="fa-solid fa-phone me-2"></i>{{ $pegawai->nomor }}</span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format((float)str_replace('.', '', $pegawai->gaji), 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-warning btn-sm text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm hover-scale" style="width: 38px; height: 38px;" data-bs-toggle="modal" data-bs-target="#editModal{{ $pegawai->id }}" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            
                                            <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm hover-scale" style="width: 38px; height: 38px;" title="Hapus">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Edit Modern -->
                                <div class="modal fade" id="editModal{{ $pegawai->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                                            <div class="modal-header border-0 bg-light px-4 py-3" style="border-bottom: 1px solid rgba(0,0,0,0.05) !important;">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fa-solid fa-user-pen fs-5"></i>
                                                    </div>
                                                    <h5 class="modal-title fw-bold text-dark m-0">Edit Data Pegawai</h5>
                                                </div>
                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST">
                                                <div class="modal-body p-4 bg-white">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Nama Lengkap</label>
                                                        <input type="text" class="form-control" name="name" value="{{ $pegawai->name }}" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Alamat Lengkap</label>
                                                        <input type="text" class="form-control" name="alamat" value="{{ $pegawai->alamat }}" required>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label text-muted small fw-bold text-uppercase">Nomor Telepon</label>
                                                            <input type="text" class="form-control" name="nomor" value="{{ $pegawai->nomor }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label text-muted small fw-bold text-uppercase">Jabatan</label>
                                                            <select class="form-select" name="jabatan" required>
                                                                <option value="Manager" {{ $pegawai->jabatan == 'Manager' ? 'selected' : '' }}>Manager</option>
                                                                <option value="Accounting" {{ $pegawai->jabatan == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                                                                <option value="Sales / Marketing" {{ $pegawai->jabatan == 'Sales / Marketing' ? 'selected' : '' }}>Sales / Marketing</option>
                                                                <option value="Teknisi" {{ $pegawai->jabatan == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                                                                <option value="Operasional" {{ $pegawai->jabatan == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Gaji Pokok</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light fw-bold text-secondary border-end-0">Rp</span>
                                                            <input type="text" class="form-control border-start-0" name="gaji" value="{{ $pegawai->gaji }}" oninput="formatRupiah(this)" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 p-4 bg-light d-flex gap-2">
                                                    <button type="button" class="btn btn-light rounded-pill px-4 flex-fill fw-bold text-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4 flex-fill shadow-sm fw-bold"><i class="fa-solid fa-check me-2"></i> Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(count($pegawaiList) == 0)
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                            <i class="fa-regular fa-folder-open text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                        <h5 class="text-dark fw-bold">Belum ada data pegawai</h5>
                        <p class="text-muted small">Tambahkan data pegawai pertama Anda sekarang.</p>
                        <a href="{{ url('/pegawai') }}" class="btn btn-primary rounded-pill px-4 mt-2 shadow-sm rounded">
                            <i class="fa-solid fa-plus me-2"></i> Tambah Pegawai
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function formatRupiah(el) {
    let value = el.value.replace(/\./g, '').replace(/[^\d]/g, '');
    if (!value) {
        el.value = '';
        return;
    }
    el.value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
</script>
<style>
.form-control, .form-select {
    border-radius: 10px;
    padding: 12px 15px;
    background-color: #f8fafc !important; /* Force light clear background for text */
    border: 1px solid #e2e8f0;
    color: #1e293b !important; /* Force text color so it's not invisible */
}
.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    border-color: var(--primary-color);
    background-color: #ffffff !important;
}
.input-group .form-control {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.input-group-text {
    border-radius: 10px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border: 1px solid #e2e8f0;
}
.input-group:focus-within .input-group-text, .input-group:focus-within .form-control {
    border-color: var(--primary-color);
}
.hover-scale { transition: transform 0.2s ease; }
.hover-scale:hover { transform: scale(1.1); }
</style>
@endsection
