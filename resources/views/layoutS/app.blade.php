<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - PT JAYA ABADI</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e293b;
            --accent-color: #3b82f6;
            --bg-color: #f8fafc;
            --card-radius: 16px;
			--success-color: #10b981;
			--danger-color: #ef4444;
			--warning-color: #f59e0b;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            background: linear-gradient(180deg, var(--primary-color) 0%, #0f172a 100%);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
            z-index: 1050;
            display: flex;
            flex-direction: column;
        }
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.2);
            padding: 12px;
            gap: 15px;
        }
        .brand img {
            max-height: 50px;
            width: auto;
            object-fit: contain;
        }
        .brand-text {
            font-size: 22px;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 40px;
            position: fixed;
            top: 0;
            left: 280px;
            width: calc(100% - 280px);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .header-title {
            color: #1e293b;
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 14px 24px;
            margin: 8px 16px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--accent-color);
            border-radius: 4px 0 0 4px;
        }
        .sidebar .nav-link i {
            width: 30px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1em;
            transition: transform 0.3s ease;
        }
        .sidebar .nav-link:hover i {
            transform: scale(1.2);
            color: var(--accent-color);
        }
        .sidebar .nav-link.active i {
            color: var(--accent-color);
        }
        .content {
            margin-left: 280px;
            padding: 115px 30px 30px;
            min-height: 100vh;
        }
        /* Cards */
        .card { 
            border: none; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.03); 
            border-radius: var(--card-radius); 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        }
        .card-header { 
            background-color: white; 
            border-bottom: 1px solid rgba(0,0,0,0.04); 
            border-radius: var(--card-radius) var(--card-radius) 0 0 !important; 
            font-weight: 600; 
            padding: 20px 24px;
        }
        
        /* Table Styles */
        .table { 
            background: white; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); 
            margin-bottom: 0;
        }
        .table thead th { 
            background-color: #f8fafc; 
            color: #475569; 
            border: none; 
            padding: 16px 20px; 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
            letter-spacing: 0.5px;
        }
        .table tbody td { 
            padding: 16px 20px; 
            vertical-align: middle; 
            border-bottom: 1px solid #f1f5f9; 
            color: #334155;
            transition: background-color 0.2s ease;
        }
        .table tbody tr:hover td {
            background-color: #f8fafc;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.3s ease;
            box-shadow: none;
        }
        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        
        /* Buttons */
        .btn { 
            border-radius: 10px; 
            font-weight: 600; 
            padding: 10px 20px; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            letter-spacing: 0.3px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        .btn-primary { background-color: var(--accent-color); border-color: var(--accent-color); }
        .btn-success { background-color: #2ecc71; border-color: #2ecc71; }
        .btn-warning { background-color: var(--accent-color); border-color: var(--accent-color); color: white; }
        
        /* Hover lift effect for cards */
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logoNew.png') }}" alt="Logo PT Jaya Abadi">
            <span class="brand-text">Jaya Abadi</span>
        </div>
        
        

        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/pegawai') }}" class="nav-link {{ request()->is('pegawai') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-plus"></i> Input Pegawai
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/daftar-pegawai') }}" class="nav-link {{ request()->is('daftar-pegawai') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Daftar Pegawai
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/absensi') }}" class="nav-link {{ request()->is('absensi') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check"></i> Absensi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/riwayat-absensi') }}" class="nav-link {{ request()->is('riwayat-absensi') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i> Histori Absen
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/histori-gaji') }}" class="nav-link {{ request()->is('histori-gaji*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-wave"></i> Histori Gaji
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/setting-libur') }}" class="nav-link {{ request()->is('setting-libur') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-times"></i> Setting Tanggal
                </a>
            </li>
        </ul>
    </div>

    <div class="header">
        <div class="header-title">@yield('title', 'Sistem Manajemen Pegawai')</div>
        <div class="d-flex align-items-center gap-4">
            <span class="badge bg-primary fs-6 py-2 px-3 shadow-sm rounded-pill">{{ date('d M Y') }}</span>
            <div class="dropdown">
                <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="dropdown" style="cursor:pointer" title="Options">
                    <div class="text-end me-3 d-none d-md-block">
                        <h6 class="mb-0 text-dark fw-bold" style="font-size: 0.95rem;">Admin User</h6>
                        <small class="text-muted d-block" style="font-size: 0.75rem; line-height: 1;">Administrator</small>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1e293b&color=fff" alt="Admin" class="rounded-circle shadow-sm" width="45" height="45">
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-3" style="border-radius: 12px; min-width: 200px;">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item text-dark py-2 fw-bold d-flex align-items-center">
                            <span class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="fa-solid fa-gear"></i>
                            </span> 
                            Pengaturan
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2 fw-bold d-flex align-items-center">
                                <span class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                    <i class="fa-solid fa-sign-out-alt"></i>
                                </span> 
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="content animate__animated animate__fadeIn">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Display Laravel Flash Messages automatically as Toasts
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if(session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif

        @if($errors->any())
            Toast.fire({
                icon: 'error',
                title: 'Terjadi kesalahan pada input data!'
            });
        @endif
        
        // Add dynamic SweetAlert Confirm to standard delete forms if any exist with class 'form-delete'
        document.querySelectorAll('.form-delete').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
    @stack('modals')
    @yield('scripts')
</body>
</html>



