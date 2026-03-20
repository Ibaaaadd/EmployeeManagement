@extends('layouts.app')

@section('title', 'Daftar Pegawai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Daftar Pegawai
            </h2>
        </div>
        
        <x-datatable id="pegawaiTable" title="Data Pegawai" icon="fa-solid fa-users-rectangle" :empty="count($pegawaiList) == 0">
            <x-slot name="action">
                <a href="{{ url('/pegawai') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                    <i class="fa-solid fa-plus me-2"></i> Tambah Pegawai
                </a>
            </x-slot>
            
            <x-slot name="head">
                <th class="ps-4 py-3 text-secondary" width="5%">No</th>
                <th class="py-3 text-secondary">Identitas Pegawai</th>
                <th class="py-3 text-secondary">Jabatan</th>
                <th class="py-3 text-secondary">Kontak</th>
                <th class="py-3 text-secondary">Gaji Pokok</th>
                <th class="text-center pe-4 py-3 text-secondary" width="10%">Aksi</th>
            </x-slot>

            <x-slot name="body">
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
                                <button type="button" class="btn btn-warning btn-sm text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm hover-scale" style="width: 38px; height: 38px;" data-bs-toggle="modal" data-bs-target="#editModal{{ $pegawai->id }}" title="Edit">
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
                @endforeach
            </x-slot>

            <x-slot name="emptyAction">
                <a href="{{ url('/pegawai') }}" class="btn btn-primary rounded-pill px-4 mt-2 shadow-sm rounded">
                    <i class="fa-solid fa-plus me-2"></i> Tambah Pegawai
                </a>
            </x-slot>
        </x-datatable>
    </div>
</div>

@endsection

@push('modals')
@foreach($pegawaiList as $pegawai)
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
                    <x-button type="submit" color="primary" icon="fa-solid fa-check" class="flex-fill">Simpan Perubahan</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endpush

@section('scripts')
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
@endsection