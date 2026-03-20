<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> PT JAYA ABADI </title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }
       .header {
            background-color: rgb(252, 168, 58);
            color: white;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 250px; 
            width: calc(100% - 250px); 
            height: 70px;
            z-index: 1000;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #2c3e50;
            color: white;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 80px 20px 20px 20px; 
            transition: margin-left 0.3s ease;
        }
        .icon-box {
            font-size: 2em;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .dropdown-menu {
        background-color: #fff;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .icon-box.blue { background-color: #007bff; }
        .icon-box.red { background-color: #dc3545; }
        .icon-box.green { background-color: #28a745; }
        .icon-box.orange { background-color: #fd7e14; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="dropdown d-flex align-items-center justify-content-start px-3 my-4">
            <img src="https://ui-avatars.com/api/?name=Admin&background=random&color=fff" alt="Admin" width="50" height="50" class="rounded-circle me-2">
            
            <a class="d-block text-decoration-none text-white dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                Admin
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa fa-sign-out me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    
    <div class="header">
        <span class="brand">PT JAYA ABADI</span>
    </div>


        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ url('/absensi') }}" class="nav-link {{ request()->is('absensi') ? 'active' : '' }}">
                    <i class="fa fa-calendar-check-o"></i> Absensi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/riwayat-absensi') }}" class="nav-link {{ request()->is('riwayat-absensi') ? 'active' : '' }}">
                    <i class="fa fa-history"></i> Riwayat Absensi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/penggajian') }}" class="nav-link {{ request()->is('penggajian') ? 'active' : '' }}">
                    <i class="fa fa-money"></i> Penggajian
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/riwayat-gaji') }}" class="nav-link {{ request()->is('riwayat-gaji') ? 'active' : '' }}">
                    <i class="fa fa-list-alt"></i> Riwayat Gaji
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/pegawai') }}" class="nav-link {{ request()->is('pegawai') ? 'active' : '' }}">
                    <i class="fa fa-user"></i> Input Pegawai
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/daftar-pegawai') }}" class="nav-link {{ request()->is('daftar-pegawai') ? 'active' : '' }}">
                    <i class="fa fa-users"></i> Daftar Pegawai
                </a>
            </li>
        </ul>
    </div>

    
   <div class="content" id="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
