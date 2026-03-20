@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h2 style="border-bottom: 2px solid #198754; padding-bottom: 10px; display: inline-block; font-family: 'Montserrat', sans-serif; font-weight: 600;">
            DAFTAR PEGAWAI
        </h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Nomor Telepon</th>
                    <th>Jabatan</th>
                    <th>Gaji Pokok</th>
                    <th>Aksi</th> 
                </tr>
            </thead>
            <tbody>
                @foreach($pegawaiList as $pegawai)
                    <tr>
                        <td>{{ $pegawai->id }}</td>
                        <td>{{ $pegawai->name }}</td>
                        <td>{{ $pegawai->alamat }}</td>
                        <td>{{ $pegawai->nomor }}</td>
                        <td>{{ $pegawai->jabatan }}</td>
                        <td>{{ $pegawai->gaji }}</td>
                        <td>
                            <!-- Tombol Edit -->
                             <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $pegawai->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $pegawai->id }}">Hapus</button>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $pegawai->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $pegawai->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $pegawai->id }}">Edit Pegawai</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama Pegawai</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $pegawai->name }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="alamat" class="form-label">Alamat</label>
                                                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $pegawai->alamat }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nomor" class="form-label">Nomor Telepon</label>
                                                    <input type="text" class="form-control" id="nomor" name="nomor"
                                                        value="{{ $pegawai->nomor }}"
                                                        pattern="\d*"
                                                        inputmode="numeric"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        maxlength="15" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jabatan" class="form-label">Jabatan</label>
                                                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ $pegawai->jabatan }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="gaji" class="form-label">Gaji Pokok</label>
                                                    <input type="text" class="form-control" id="gaji" name="gaji"
                                                        value="{{ $pegawai->gaji }}"
                                                        pattern="\d*"
                                                        inputmode="numeric"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update Pegawai</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Delete -->
                            <div class="modal fade" id="deleteModal{{ $pegawai->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $pegawai->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $pegawai->id }}">Hapus Pegawai</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus pegawai {{ $pegawai->name }}?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endsection
