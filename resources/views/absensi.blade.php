@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
                <h2
                    style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                    Absensi Pegawai
                </h2>
            </div>

            <div class="row g-4">
                @php
                    // Cek apakah user sudah absen hari ini
                    $sudahAbsenHariIni = false;
                    $absensiHariIni = null;

                    if (Auth::check() && Auth::user()->pegawai_id) {
                        $today = \Carbon\Carbon::today();
                        $absensiHariIni = \App\Models\Absensi::where('pegawai_id', Auth::user()->pegawai_id)
                            ->whereDate('attendance_time', $today)
                            ->first();
                        $sudahAbsenHariIni = $absensiHariIni !== null;
                    }
                @endphp

                @if (Auth::check() && Auth::user()->role === 'user' && $sudahAbsenHariIni)
                    {{-- User sudah absen: Tampilkan status card saja --}}
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
                            <div class="card-header bg-white py-3 px-4"
                                style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                                <h6 class="mb-0 text-dark fw-bold"><i
                                        class="fa-solid fa-circle-check text-success me-2"></i> Absensi Hari Ini</h6>
                            </div>
                            <div class="card-body p-4">
                                @if ($absensiHariIni->status === 'Hadir')
                                    @php
                                        $sudahAbsenMasuk = $absensiHariIni->jam_masuk;
                                        $sudahAbsenPulang = $absensiHariIni->jam_pulang;
                                    @endphp

                                    @if ($sudahAbsenMasuk && $sudahAbsenPulang)
                                        {{-- Sudah lengkap (masuk & pulang) --}}
                                        <div class="modern-status-card gradient-primary">
                                            <div class="status-icon-large">
                                                <i class="fa-solid fa-calendar-check"></i>
                                            </div>
                                            <div class="status-content">
                                                <h5 class="status-title-modern">Absensi Lengkap!</h5>
                                                <p class="status-subtitle-modern">Anda telah menyelesaikan absensi hari ini</p>
                                                <div class="time-info-modern">
                                                    <div class="time-item-modern">
                                                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                                        <span class="time-label-modern">Masuk</span>
                                                        <span
                                                            class="time-value-modern">{{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}</span>
                                                    </div>
                                                    <div class="time-item-modern">
                                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                                        <span class="time-label-modern">Pulang</span>
                                                        <span
                                                            class="time-value-modern">{{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') }}</span>
                                                    </div>
                                                </div>
                                                @if ($absensiHariIni->is_late)
                                                    <div class="badge-status badge-late">
                                                        <i class="fa-solid fa-clock me-1"></i> Terlambat
                                                    </div>
                                                @else
                                                    <div class="badge-status badge-ontime">
                                                        <i class="fa-solid fa-thumbs-up me-1"></i> Tepat Waktu
                                                    </div>
                                                @endif
                                                <p class="thank-you-text">Terima kasih! Sampai jumpa besok 👋</p>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Baru absen masuk, belum pulang --}}
                                        <div class="modern-status-card gradient-success">
                                            <div class="status-icon-large">
                                                <i class="fa-solid fa-check-circle"></i>
                                            </div>
                                            <div class="status-content">
                                                <h5 class="status-title-modern">✅ Sudah Absen Masuk</h5>
                                                <p class="status-subtitle-modern">Anda telah melakukan absensi masuk hari ini</p>
                                                <div class="time-single">
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span class="time-label">Jam Masuk:</span>
                                                    <span
                                                        class="time-value">{{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}
                                                        WIB</span>
                                                </div>
                                                @if ($absensiHariIni->is_late)
                                                    <div class="badge-status badge-late">
                                                        <i class="fa-solid fa-clock me-1"></i> Terlambat
                                                    </div>
                                                @else
                                                    <div class="badge-status badge-ontime">
                                                        <i class="fa-solid fa-thumbs-up me-1"></i> Tepat Waktu
                                                    </div>
                                                @endif
                                                <div class="reminder-box">
                                                    <i class="fa-solid fa-bell"></i>
                                                    <p>Jangan lupa absen pulang nanti ya! 😊</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($absensiHariIni->status === 'Izin')
                                    {{-- Status Izin --}}
                                    <div class="modern-status-card gradient-warning">
                                        <div class="status-icon-large">
                                            <i class="fa-solid fa-envelope-open-text"></i>
                                        </div>
                                        <div class="status-content">
                                            <h5 class="status-title-modern">📝 Status: Izin</h5>
                                            <p class="status-subtitle-modern">Anda telah mengajukan izin untuk hari ini</p>
                                            <div class="time-single">
                                                <i class="fa-solid fa-calendar-xmark"></i>
                                                <span class="time-label">Waktu Pengajuan:</span>
                                                <span
                                                    class="time-value">{{ \Carbon\Carbon::parse($absensiHariIni->attendance_time)->format('H:i') }}
                                                    WIB</span>
                                            </div>
                                            <p class="thank-you-text">Semoga cepat sembuh/selesai urusannya! 🙏</p>
                                        </div>
                                    </div>
                                @else
                                    {{-- Status Tidak Hadir --}}
                                    <div class="modern-status-card gradient-danger">
                                        <div class="status-icon-large">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </div>
                                        <div class="status-content">
                                            <h5 class="status-title-modern">❌ Status: Tidak Hadir</h5>
                                            <p class="status-subtitle-modern">Tercatat tidak hadir pada hari ini</p>
                                            <div class="time-single">
                                                <i class="fa-solid fa-calendar-minus"></i>
                                                <span class="time-label">Waktu Pencatatan:</span>
                                                <span
                                                    class="time-value">{{ \Carbon\Carbon::parse($absensiHariIni->attendance_time)->format('H:i') }}
                                                    WIB</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-4 text-center">
                                    <a href="{{ route('absensi.riwayat') }}"
                                        class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="fa-solid fa-history me-2"></i> Lihat Riwayat Absensi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Belum absen atau Admin: Tampilkan form --}}
                    <!-- Input Form Absensi -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm animate__animated animate__fadeInLeft"
                            style="border-radius: 20px;">
                            <div class="card-header bg-white py-3 px-4"
                                style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                                <h6 class="mb-0 text-dark fw-bold"><i
                                        class="fa-solid fa-clipboard-user text-primary me-2"></i> Form Absensi</h6>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data"
                                    id="absensiForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="employee_id"
                                            class="form-label fw-bold text-secondary small text-uppercase">Nama
                                            Pegawai</label>
                                        @auth
                                            @if (Auth::user()->role === 'admin')
                                                {{-- Admin bisa pilih semua pegawai --}}
                                                <select class="form-select w-100 select-search" id="employee_id"
                                                    name="employee_id" required>
                                                    <option value="" disabled selected>-- Pilih Pegawai --</option>
                                                    @foreach ($pegawaiList as $pegawai)
                                                        <option value="{{ $pegawai->id }}">{{ $pegawai->name }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                {{-- User biasa hanya bisa absen untuk dirinya sendiri --}}
                                                @if (Auth::user()->pegawai_id && $pegawaiList->isNotEmpty())
                                                    <input type="text" class="form-control"
                                                        value="{{ $pegawaiList->first()->name }}" disabled readonly>
                                                    <input type="hidden" name="employee_id"
                                                        value="{{ Auth::user()->pegawai_id }}">
                                                    <small class="text-muted">Anda hanya dapat melakukan absensi untuk diri
                                                        sendiri</small>
                                                @else
                                                    <input type="text" class="form-control"
                                                        value="Data pegawai tidak ditemukan" disabled readonly>
                                                    <small class="text-danger">Hubungi administrator untuk menghubungkan akun
                                                        Anda dengan data pegawai</small>
                                                @endif
                                            @endif
                                        @else
                                            <input type="text" class="form-control" value="Silakan login terlebih dahulu"
                                                disabled readonly>
                                        @endauth
                                    </div>

                                    <div class="mb-3">
                                        <label
                                            class="form-label fw-bold text-secondary small text-uppercase d-block mb-2">Status
                                            Kehadiran</label>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <input type="radio" class="btn-check" name="status" id="hadir"
                                                value="Hadir" autocomplete="off" onclick="toggleCamera()" required>
                                            <label
                                                class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold custom-btn-check"
                                                for="hadir"><i class="fa-solid fa-check-circle me-1"></i> Hadir</label>

                                            <input type="radio" class="btn-check" name="status" id="izin"
                                                value="Izin" autocomplete="off" onclick="toggleCamera()">
                                            <label
                                                class="btn btn-outline-warning rounded-pill px-4 py-2 fw-bold custom-btn-check"
                                                for="izin"><i class="fa-solid fa-envelope-open-text me-1"></i>
                                                Izin</label>

                                            <input type="radio" class="btn-check" name="status" id="tidak_hadir"
                                                value="Tidak Hadir" autocomplete="off" onclick="toggleCamera()">
                                            <label
                                                class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold custom-btn-check"
                                                for="tidak_hadir"><i class="fa-solid fa-circle-xmark me-1"></i> Tidak
                                                Hadir</label>
                                        </div>
                                    </div>

                                    <div class="mb-3" id="tipe_absen_div" style="display: none;">
                                        <label
                                            class="form-label fw-bold text-secondary small text-uppercase d-block mb-2">Tipe
                                            Absen</label>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <input type="radio" class="btn-check" name="tipe_absen" id="masuk"
                                                value="masuk" autocomplete="off">
                                            <label
                                                class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold custom-btn-check"
                                                for="masuk"><i class="fa-solid fa-arrow-right-to-bracket me-1"></i>
                                                Masuk (Max 08:00 WIB)</label>

                                            <input type="radio" class="btn-check" name="tipe_absen" id="pulang"
                                                value="pulang" autocomplete="off">
                                            <label
                                                class="btn btn-outline-info rounded-pill px-4 py-2 fw-bold custom-btn-check"
                                                for="pulang"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i>
                                                Pulang</label>
                                        </div>
                                    </div>

                                    <div class="mb-3 text-center p-3 bg-light rounded-3" id="attendance_photo_div"
                                        style="display: none; border: 2px dashed #cbd5e1;">
                                        <label class="form-label fw-bold text-secondary small text-uppercase mb-2">Ambil
                                            Foto Kehadiran</label>
                                        <div class="webcam-container mx-auto mb-2"
                                            style="width: 320px; height: 240px; overflow: hidden; border-radius: 12px; background: #000; position: relative;">
                                            <video id="webcam" width="320" height="240" autoplay
                                                style="object-fit: cover;"></video>
                                        </div>
                                        <canvas id="canvas" style="display:none;"></canvas>

                                        <button type="button" class="btn btn-primary rounded-pill px-4 mb-2"
                                            id="takePhotoBtn">
                                            <i class="fa-solid fa-camera me-2"></i> Ambil Foto
                                        </button>

                                        <div class="mt-2">
                                            <img id="photoPreview" src="" class="rounded-3 shadow-sm mx-auto"
                                                style="display:none; width: 320px; height: 240px; border: 3px solid #fff; object-fit: cover;">
                                        </div>
                                        <input type="hidden" name="attendance_photo" id="attendance_photo" required
                                            disabled>
                                    </div>

                                    <input type="hidden" id="attendance_time" name="attendance_time">

                                    <div class="mt-4 text-end">
                                        <button type="submit"
                                            class="btn btn-success fw-bold text-white rounded-pill px-5 py-2 shadow-sm d-inline-flex align-items-center"
                                            id="absenButton" style="background-color: var(--success-color);">
                                            <i class="fa-solid fa-paper-plane me-2"></i> Kirim Absensi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @auth
                        @if (Auth::user()->role === 'admin')
                            <!-- Admin: Tabel Pegawai Belum Absen -->
                            <div class="col-md-12 mt-4">
                                @component('components.datatable', [
                                    'id' => 'tabelBelumAbsen',
                                    'title' => 'Daftar Pegawai Belum Absen Hari Ini',
                                    'icon' => 'fa-solid fa-clock-rotate-left',
                                    'empty' => $pegawaiBelumAbsen->isEmpty(),
                                ])
                                    @slot('head')
                                        <th class="ps-4">Profil</th>
                                        <th>Nama Pegawai</th>
                                        <th>Jabatan</th>
                                        <th class="text-center">Status</th>
                                    @endslot

                                    @slot('body')
                                        <div class="desktop-view-table">
                                            @foreach ($pegawaiBelumAbsen as $pegawai)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="bg-light text-secondary rounded-circle d-flex justify-content-center align-items-center shadow-sm"
                                                            style="width: 40px; height: 40px; font-weight: bold;">
                                                            {{ strtoupper(substr($pegawai->name, 0, 1)) }}
                                                        </div>
                                                    </td>
                                                    <td class="fw-bold text-dark">{{ $pegawai->name }}</td>
                                                    <td class="text-muted">{{ $pegawai->jabatan ?? '-' }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2">
                                                            <i class="fa-solid fa-hourglass-half me-1"></i> Pending
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </div>

                                        {{-- Mobile Card View --}}
                                        <div class="mobile-view-card">
                                            @foreach ($pegawaiBelumAbsen as $pegawai)
                                                <div class="absence-card mb-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="avatar-circle-absence">
                                                            {{ strtoupper(substr($pegawai->name, 0, 1)) }}
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="fw-bold text-dark mb-1">{{ $pegawai->name }}</h6>
                                                            <small class="text-muted">{{ $pegawai->jabatan ?? '-' }}</small>
                                                        </div>
                                                        <span class="badge bg-danger text-white rounded-pill px-3 py-2">
                                                            <i class="fa-solid fa-hourglass-half me-1"></i> Pending
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endslot

                                    @if (!$pegawaiBelumAbsen->isEmpty())
                                        @slot('pagination')
                                            <div class="modern-pagination">
                                                {{ $pegawaiBelumAbsen->links() }}
                                            </div>
                                        @endslot
                                    @endif
                                @endcomponent
                            </div>
                        @else
                            <!-- User: Status Absensi Card -->
                            <div class="col-md-12 mt-4 user-attendance-status">
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $userAbsensiToday = null;
                                    if (Auth::user()->pegawai_id) {
                                        $userAbsensiToday = \App\Models\Absensi::where(
                                            'pegawai_id',
                                            Auth::user()->pegawai_id,
                                        )
                                            ->whereDate('attendance_time', $today)
                                            ->first();
                                    }

                                    $jamMasukWaktu = \Carbon\Carbon::today()->setTime(8, 0, 0); // 08:00
                                    $batasReminderPulang = \Carbon\Carbon::today()->setTime(8, 30, 0); // 08:30
                                    $now = \Carbon\Carbon::now();

                                    $sudahAbsenMasuk = $userAbsensiToday && $userAbsensiToday->jam_masuk;
                                    $sudahAbsenPulang = $userAbsensiToday && $userAbsensiToday->jam_pulang;
                                    $perluReminderPulang =
                                        $sudahAbsenMasuk &&
                                        !$sudahAbsenPulang &&
                                        $now->greaterThan($batasReminderPulang);
                                @endphp

                                <div class="card border-0 shadow-sm animate__animated animate__fadeInUp"
                                    style="border-radius: 20px;">
                                    <div class="card-header bg-white py-3 px-4"
                                        style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                                        <h6 class="mb-0 text-dark fw-bold"><i
                                                class="fa-solid fa-user-clock text-info me-2"></i> Status Absensi Anda Hari Ini
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        @if (!$userAbsensiToday)
                                            {{-- Belum absen sama sekali --}}
                                            <div class="user-status-card bg-warning bg-opacity-10 border border-warning">
                                                <div class="status-icon bg-warning">
                                                    <i class="fa-solid fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="status-content">
                                                    <h6 class="status-title text-warning">Anda Belum Melakukan Absensi</h6>
                                                    <p class="status-message mb-0">Silakan lakukan absensi masuk menggunakan
                                                        form di atas.</p>
                                                </div>
                                            </div>
                                        @elseif($sudahAbsenMasuk && !$sudahAbsenPulang)
                                            {{-- Sudah absen masuk, belum pulang --}}
                                            <div class="user-status-card bg-success bg-opacity-10 border border-success mb-3">
                                                <div class="status-icon bg-success">
                                                    <i class="fa-solid fa-check-circle"></i>
                                                </div>
                                                <div class="status-content">
                                                    <h6 class="status-title text-success">✅ Anda Sudah Absen Masuk</h6>
                                                    <p class="status-message mb-2">
                                                        <i class="fa-regular fa-clock me-1"></i> Jam Masuk:
                                                        <strong>{{ \Carbon\Carbon::parse($userAbsensiToday->jam_masuk)->format('H:i') }}</strong>
                                                    </p>
                                                    @if ($userAbsensiToday->is_late)
                                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1">
                                                            <i class="fa-solid fa-clock me-1"></i> Terlambat
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success text-white rounded-pill px-3 py-1">
                                                            <i class="fa-solid fa-thumbs-up me-1"></i> Tepat Waktu
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($perluReminderPulang)
                                                {{-- Reminder absen pulang --}}
                                                <div class="user-status-card bg-info bg-opacity-10 border border-info">
                                                    <div class="status-icon bg-info">
                                                        <i class="fa-solid fa-bell"></i>
                                                    </div>
                                                    <div class="status-content">
                                                        <h6 class="status-title text-info">📢 Jangan Lupa Absen Pulang!</h6>
                                                        <p class="status-message mb-0">Segera lakukan absensi pulang setelah
                                                            selesai bekerja.</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @elseif($sudahAbsenMasuk && $sudahAbsenPulang)
                                            {{-- Sudah absen masuk dan pulang --}}
                                            <div class="user-status-card bg-primary bg-opacity-10 border border-primary">
                                                <div class="status-icon bg-primary">
                                                    <i class="fa-solid fa-calendar-check"></i>
                                                </div>
                                                <div class="status-content">
                                                    <h6 class="status-title text-primary">🎉 Absensi Hari Ini Lengkap!</h6>
                                                    <div class="row g-2 mt-2">
                                                        <div class="col-6">
                                                            <div class="time-badge">
                                                                <small class="text-muted d-block">Masuk</small>
                                                                <strong class="text-success"><i
                                                                        class="fa-solid fa-arrow-right-to-bracket me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($userAbsensiToday->jam_masuk)->format('H:i') }}</strong>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="time-badge">
                                                                <small class="text-muted d-block">Pulang</small>
                                                                <strong class="text-danger"><i
                                                                        class="fa-solid fa-arrow-right-from-bracket me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($userAbsensiToday->jam_pulang)->format('H:i') }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="status-message mb-0 mt-3">Terima kasih telah menyelesaikan
                                                        absensi hari ini. Sampai jumpa besok! 👋</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleCamera() {
            var status = document.querySelector('input[name="status"]:checked').value;
            var attendancePhotoDiv = document.getElementById("attendance_photo_div");
            var tipeAbsenDiv = document.getElementById("tipe_absen_div");
            var webcam = document.getElementById("webcam");
            var takePhotoBtn = document.getElementById("takePhotoBtn");
            var photoInput = document.getElementById("attendance_photo");
            var tipeAbsenInputs = document.querySelectorAll('input[name="tipe_absen"]');

            if (status === "Hadir") {
                attendancePhotoDiv.style.display = "block";
                tipeAbsenDiv.style.display = "block";
                takePhotoBtn.style.display = "inline-block";
                photoInput.disabled = false;
                tipeAbsenInputs.forEach(input => input.required = true);
                startCamera();
            } else {
                attendancePhotoDiv.style.display = "none";
                tipeAbsenDiv.style.display = "none";
                takePhotoBtn.style.display = "none";
                webcam.style.display = "none";
                photoInput.disabled = true;
                photoInput.value = "";
                tipeAbsenInputs.forEach(input => {
                    input.required = false;
                    input.checked = false;
                });
                stopCamera();
            }
        }

        function startCamera() {
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(stream) {
                    var webcam = document.getElementById('webcam');
                    webcam.srcObject = stream;
                    webcam.style.display = 'block';
                    window.stream = stream;
                })
                .catch(function(err) {
                    Swal.fire('Oops...', 'Tidak dapat mengakses kamera! Pastikan izin kamera diaktifkan.', 'error');
                    console.log("Error accessing webcam: " + err);
                });
        }

        function stopCamera() {
            var webcam = document.getElementById('webcam');
            if (window.stream) {
                window.stream.getTracks().forEach(track => track.stop());
                webcam.style.display = 'none';
            }
        }

        function takePhoto() {
            var canvas = document.getElementById('canvas');
            var webcam = document.getElementById('webcam');
            var context = canvas.getContext('2d');
            canvas.width = webcam.videoWidth || 320;
            canvas.height = webcam.videoHeight || 240;
            context.drawImage(webcam, 0, 0, canvas.width, canvas.height);

            var photo = canvas.toDataURL('image/png');
            document.getElementById('attendance_photo').value = photo;

            var photoPreview = document.getElementById('photoPreview');
            photoPreview.src = photo;
            photoPreview.style.display = 'block';

            webcam.style.display = 'none';
            takePhotoBtn.innerHTML = '<i class="fa-solid fa-camera-rotate me-2"></i> Foto Ulang';

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Foto berhasil diambil!',
                showConfirmButton: false,
                timer: 2000
            });
        }

        document.getElementById("takePhotoBtn").addEventListener("click", function() {
            var webcam = document.getElementById('webcam');
            if (webcam.style.display === 'none') {
                // User click "Foto Ulang"
                webcam.style.display = 'block';
                document.getElementById('photoPreview').style.display = 'none';
                this.innerHTML = '<i class="fa-solid fa-camera me-2"></i> Ambil Foto';
                document.getElementById('attendance_photo').value = '';
            } else {
                takePhoto();
            }
        });

        document.getElementById("absensiForm").addEventListener("submit", function(event) {
            var employeeInput = document.getElementById("employee_id") || document.querySelector(
                'input[name="employee_id"]');
            var employeeId = employeeInput ? employeeInput.value : null;
            var status = document.querySelector('input[name="status"]:checked');
            var photo = document.getElementById('attendance_photo').value;
            var now = new Date();

            var year = now.getFullYear();
            var month = String(now.getMonth() + 1).padStart(2, '0');
            var day = String(now.getDate()).padStart(2, '0');
            var hours = String(now.getHours()).padStart(2, '0');
            var minutes = String(now.getMinutes()).padStart(2, '0');
            var seconds = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('attendance_time').value = year + '-' + month + '-' + day + ' ' + hours + ':' +
                minutes + ':' + seconds;

            if (!employeeId || !status) {
                event.preventDefault();
                Swal.fire('Peringatan', 'Silakan pilih Nama Pegawai dan Status Kehadiran!', 'warning');
                return;
            }

            if (status.value === 'Hadir' && !photo) {
                event.preventDefault();
                Swal.fire('Peringatan', 'Foto kehadiran wajib diambil untuk status Hadir!', 'warning');
                return;
            }

            Swal.fire({
                title: 'Memproses Absensi...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    </script>
    <style>
        /* Override default bootstrap outline button issues to strictly enforce colors */
        .btn-outline-success.custom-btn-check {
            color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-outline-success.custom-btn-check:hover,
        .btn-check:checked+.btn-outline-success.custom-btn-check {
            background-color: var(--success-color) !important;
            color: #ffffff !important;
            border-color: var(--success-color) !important;
        }

        .btn-outline-warning.custom-btn-check {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-outline-warning.custom-btn-check:hover,
        .btn-check:checked+.btn-outline-warning.custom-btn-check {
            background-color: var(--accent-color) !important;
            color: #ffffff !important;
            border-color: var(--accent-color) !important;
        }

        .btn-outline-danger.custom-btn-check {
            color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-outline-danger.custom-btn-check:hover,
        .btn-check:checked+.btn-outline-danger.custom-btn-check {
            background-color: var(--danger-color) !important;
            color: #ffffff !important;
            border-color: var(--danger-color) !important;
        }

        .custom-btn-check {
            transition: all 0.2s ease;
        }

        .btn-check:checked+.custom-btn-check {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .list-group-item {
            transition: background-color 0.2s;
        }

        .list-group-item:hover {
            background-color: #f8fafc;
        }

        /* Mobile-first improvement for absensi page */
        .webcam-container,
        #photoPreview {
            width: min(100%, 320px) !important;
            height: auto !important;
            aspect-ratio: 4 / 3;
        }

        #webcam {
            width: 100%;
            height: 100%;
        }

        @media (max-width: 767.98px) {
            h2 {
                font-size: 1.15rem;
                line-height: 1.4;
            }

            .card-body {
                padding: 1rem !important;
            }

            .custom-btn-check {
                width: 100%;
                text-align: center;
            }

            .d-flex.gap-2.flex-wrap {
                display: grid !important;
                grid-template-columns: 1fr;
            }

            .mt-4.text-end {
                text-align: center !important;
            }

            #absenButton {
                width: 100%;
                justify-content: center;
            }

            #attendance_photo_div {
                padding: 1rem !important;
            }
        }

        /* Absence Card Styles */
        .absence-card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 14px;
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .absence-card:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            transform: translateY(-1px);
        }

        .avatar-circle-absence {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* Desktop/Mobile Toggle for Datatable */
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

        /* User Status Card */
        .user-status-card {
            padding: 20px;
            border-radius: 16px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            transition: all 0.3s ease;
        }

        .user-status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .status-icon.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .status-icon.bg-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .status-icon.bg-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .status-icon.bg-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .status-content {
            flex: 1;
        }

        .status-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .status-message {
            color: #64748b;
            line-height: 1.6;
        }

        .time-badge {
            background: #f8fafc;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
        }

        .time-badge strong {
            font-size: 1.1rem;
            display: block;
            margin-top: 4px;
        }

        @media (max-width: 767.98px) {
            .desktop-view-table {
                display: none !important;
            }

            .mobile-view-card {
                display: block !important;
            }

            .user-status-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 16px;
            }

            .status-icon {
                width: 60px;
                height: 60px;
                font-size: 1.8rem;
            }

            .status-title {
                font-size: 1rem;
            }

            .time-badge strong {
                font-size: 1rem;
            }
            
            .modern-status-card {
                padding: 20px !important;
            }
            
            .status-icon-large {
                width: 70px !important;
                height: 70px !important;
                font-size: 2rem !important;
            }
            
            .time-info-modern {
                flex-direction: column !important;
                gap: 12px !important;
            }
        }
        
        /* ===== MODERN STATUS CARDS STYLES ===== */
        .modern-status-card {
            position: relative;
            padding: 32px;
            border-radius: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.6s ease-out;
        }

        .modern-status-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            pointer-events: none;
        }

        .modern-status-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
        }

        /* Gradient Backgrounds */
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .gradient-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }

        .status-icon-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 24px;
            animation: scaleIn 0.5s ease-out 0.2s both;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .status-title-modern {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 12px;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInDown 0.6s ease-out 0.3s both;
        }

        .status-subtitle-modern {
            font-size: 1.05rem;
            opacity: 0.95;
            text-align: center;
            margin-bottom: 24px;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        .time-info-modern {
            display: flex;
            gap: 16px;
            margin: 24px 0;
            justify-content: center;
            flex-wrap: wrap;
        }

        .time-item-modern {
            flex: 1;
            min-width: 140px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out 0.5s both;
        }

        .time-item-modern:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.3);
        }

        .time-item-modern i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 8px;
            opacity: 0.9;
        }

        .time-label-modern {
            display: block;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            opacity: 0.85;
            font-weight: 600;
        }

        .time-value-modern {
            display: block;
            font-size: 1.75rem;
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .time-single {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 16px 24px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin: 16px auto;
            animation: fadeInUp 0.6s ease-out 0.5s both;
        }

        .time-single i {
            font-size: 1.25rem;
        }

        .badge-status {
            display: inline-block;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            margin: 12px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: fadeInUp 0.6s ease-out 0.6s both;
        }

        .badge-late {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .badge-ontime {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .thank-you-text {
            text-align: center;
            margin-top: 20px;
            font-size: 1.05rem;
            opacity: 0.95;
            animation: fadeInUp 0.6s ease-out 0.7s both;
        }

        .reminder-box {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 16px;
            border-radius: 12px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeInUp 0.6s ease-out 0.6s both;
        }

        .reminder-box i {
            font-size: 1.5rem;
            animation: ring 2s ease-in-out infinite;
        }

        .reminder-box p {
            margin: 0;
            font-size: 1rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes ring {
            0%, 100% {
                transform: rotate(0deg);
            }
            10%, 30% {
                transform: rotate(-10deg);
            }
            20%, 40% {
                transform: rotate(10deg);
            }
        }

        /* Enhanced Button Styles */
        .btn-outline-primary.rounded-pill {
            background: white;
            border: 2px solid white;
            color: var(--primary-color);
            font-weight: 700;
            padding: 12px 32px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-primary.rounded-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            background: var(--primary-color);
            color: white;
        }
    </style>

    @section('scripts')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (document.querySelector('.select-search')) {
                    new TomSelect('.select-search', {
                        create: false,
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });
                }
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
                border-color: var(--accent-color);
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            }
        </style>
    @endsection
@endsection
