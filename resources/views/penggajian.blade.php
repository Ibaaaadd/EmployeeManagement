@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <h2 style="border-bottom: 2px solid #198754; padding-bottom: 10px; display: inline-block; font-family: 'Montserrat', sans-serif; font-weight: 600;">
            PENGGAJIAN
        </h2>
    {{-- Form Penggajian --}}
    <form action="{{ route('gaji.hitung') }}" method="POST">
        @csrf

        {{-- Pilih Pegawai --}}
        <div class="mb-3 col-md-6">
            <label for="pegawai" class="form-label"> Pilih Pegawai </label>
            <select id="pegawai" name="pegawai_id" class="form-select" required>
                <option value="" selected>Pilih Pegawai</option>
                @foreach($pegawaiList as $pegawaiItem)
                    <option value="{{ $pegawaiItem->id }}" 
                            data-gaji="{{ $pegawaiItem->gaji }}" 
                            {{ isset($pegawaiId) && $pegawaiId == $pegawaiItem->id ? 'selected' : '' }}>
                        {{ $pegawaiItem->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Gaji Pokok -->
        <div class="mb-3 col-md-6">
            <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
            <input type="text" id="gaji_pokok" class="form-control" readonly>
        </div>
        
        {{-- Pilih Bulan --}}
        <div class="mb-3 col-md-6">
            <label for="bulan" class="form-label">Pilih Bulan</label>
            <input type="month" id="bulan" name="bulan" class="form-control @error('bulan') is-invalid @enderror" value="{{ old('bulan') }}" required>
            @error('bulan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Pilih Periode --}}
        <div class="mb-3 col-md-6">
            <label for="periode" class="form-label">Periode Gaji</label>
            <select id="periode" name="periode" class="form-select @error('periode') is-invalid @enderror" required>
                <option value="1" {{ old('periode') == '1' ? 'selected' : '' }}>Periode 1 (Tanggal 1 - 15)</option>
                <option value="2" {{ old('periode') == '2' ? 'selected' : '' }}>Periode 2 (Tanggal 16 - Akhir Bulan)</option>
            </select>
            @error('periode')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Insentif --}}
        <div class="mb-3 col-md-6">
            <label for="insentif" class="form-label">Insentif</label>
            <input type="text" name="insentif" id="insentif" class="form-control @error('insentif') is-invalid @enderror" value="{{ old('insentif', 0) }}" min="0" required>
            @error('insentif')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Data Absensi --}}
        <h4>Data Absensi</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Izin</th>
                    <th>Tidak Hadir</th>
                    <th>Jumlah Pengurangan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="izin">{{ isset($jumlahIzin) ? $jumlahIzin : 0 }}</td>
                    <td id="tidak_hadir">{{ isset($jumlahTidakHadir) ? $jumlahTidakHadir : 0 }}</td>
                    <td id="pengurangan">{{ isset($potongan) ? number_format($potongan, 0, ',', '.') : 0 }}</td>
                </tr>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Hitung Gaji</button>
    </form>

    {{-- Hasil Perhitungan --}}
    @isset($totalGaji)
    <hr>
    <div id="slip-gaji-content">    
        <div style="text-align: center; margin-bottom: 10px;">
            <h1 style="font-family: 'Times New Roman', Times, serif; color: orange; margin: 0; font-weight: bold; font-style: italic;">
                PT. Sarina Indika Makmur
            </h1>
            <br>
            <p style="margin: 0; font-family: 'Times New Roman', Times, serif;">JL. Jend Sudirman No.41/49 A Palembang</p>
            <p style="margin: 0; font-family: 'Times New Roman', Times, serif;">Telp (0711) 313414/310562 Fax (0711) 312226</p>
            <hr style="border: 1px solid black; margin-top: 8px;">
        </div>
     
        <h3 style="margin-top: 10px; font-weight: bold; text-align: left;">
            SLIP GAJI PEGAWAI
        </h3>
        <p><strong>Nama</strong> : {{ $pegawai->name }}</p>
        <p><strong>Jabatan</strong> : {{ $pegawai->jabatan }}</p>
        <p><strong>Periode</strong> : {{ $periode == 1 ? '1 - 15' : '16 - Akhir Bulan' }} {{ date('F Y', strtotime($bulanTahun)) }}</p>

        <table class="table table-bordered mt-4" style="width: 100%;">
            <thead>
                <tr>
                    <th>Komponen</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gaji Pokok</td>
                    <td>Rp {{ number_format($gajiPokok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Insentif</td>
                    <td>Rp {{ number_format($insentif, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-end"><strong>Total</strong></td>
                    <td><strong>Rp {{ number_format($gajiPokok + $insentif, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <th colspan="2">Pengurangan</th>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td>{{ $jumlahIzin }} x Rp 30.000</td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan</td>
                    <td>{{ $jumlahTidakHadir }} x Rp 30.000</td>
                </tr>
                <tr>
                    <td class="text-end"><strong>Total Pengurangan</strong></td>
                    <td><strong>Rp {{ number_format($totalPengurangan, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td style="width: 50%; text-align: right;"><strong>TOTAL GAJI</strong></td>
                    <td style="width: 50%;"><strong>Rp {{ number_format($totalGaji, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 50px;">
            <p>Palembang, {{ date('d F Y') }}</p>
    
            <p style="margin-bottom: 0;"><strong>Pegawai yang bersangkutan, </strong></p>
            <p style="margin-top: 60px;"><strong>______________________</strong></p>
        </div>
    </div>

  
        <button onclick="printSlip()" class="btn btn-success mt-3">Cetak Slip Gaji</button>
@endisset

@if(isset($totalGaji))
<form action="{{ route('slipgaji.download') }}" method="POST" target="_blank">
    @csrf
    <input type="hidden" name="pegawai_id" value="{{ $pegawai->id }}">
    <input type="hidden" name="periode" value="{{ $periode }}">
    <input type="hidden" name="bulan" value="{{ $bulanTahun }}">
    <input type="hidden" name="gaji_pokok" value="{{ $gajiPokok }}">
    <input type="hidden" name="insentif" value="{{ $insentif }}">
    <input type="hidden" name="potongan" value="{{ $totalPengurangan }}">
    <input type="hidden" name="total_gaji" value="{{ $totalGaji }}">

    <button type="submit" class="btn btn-success mt-3">Download PDF</button>
</form>
@endif

<script>
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.getElementById('pegawai').addEventListener('change', function () {
    const gaji = this.options[this.selectedIndex].getAttribute('data-gaji') || 0;
    document.getElementById('gaji_pokok').value = formatRupiah(gaji);

    document.getElementById('izin').innerText = '0';
    document.getElementById('tidak_hadir').innerText = '0';
    document.getElementById('pengurangan').innerText = '0';
    
    });

    @isset($pegawai)
        document.getElementById('gaji_pokok').value = formatRupiah({{ $pegawai->gaji }});
    @endisset
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function fetchAbsensi() {
        const pegawaiId = $('#pegawai').val();
        const periode = $('#periode').val();
        const bulan = $('#bulan').val();

        if(pegawaiId && periode && bulan) {
            $.ajax({
                url: "{{ route('api.absensi') }}",
                type: 'GET',
                data: {
                    pegawai_id: pegawaiId,
                    periode: periode,
                    bulan: bulan
                },
                success: function(response) {
                    $('#izin').text(response.izin);
                    $('#tidak_hadir').text(response.tidak_hadir);
                    $('#pengurangan').text(response.potongan.toLocaleString('id-ID'));
                },
                error: function() {
                    $('#izin').text('0');
                    $('#tidak_hadir').text('0');
                    $('#pengurangan').text('0');
                }
            });
        } else {
            $('#izin').text('0');
            $('#tidak_hadir').text('0');
            $('#pengurangan').text('0');
        }
    }

    
    $('#pegawai, #periode, #bulan').on('change', fetchAbsensi);

    
    $(document).ready(function() {
        fetchAbsensi();
    });
</script>

<script>
    function printSlip() {
    var printContent = document.getElementById("slip-gaji-content");  // Get content to print
    
    if (!printContent) {
        console.log('Slip gaji content tidak ditemukan');
        return; 
    }

    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Slip Gaji</title>');
    printWindow.document.write('<style>body { font-family: Arial, sans-serif; margin: 20px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
    printWindow.document.write('table, th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
    printWindow.document.write('</head><body>');
    
    console.log(printContent.outerHTML);
    
    printWindow.document.write(printContent.outerHTML);  
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();  
    printWindow.print();  
}

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const insentifInput = document.getElementById('insentif');

        insentifInput.addEventListener('input', function (e) {
            let value = e.target.value;
            value = value.replace(/\./g, '');
            if (!/^\d*$/.test(value)) {
                value = value.replace(/[^\d]/g, '');
            }
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            e.target.value = formatted;
        });
    });
</script>


@endsection
