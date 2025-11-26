<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Maintenance Dashboard') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-width: 240px;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #ffffff;
            z-index: 1000;
            transition: all 0.3s ease;
            border-right: 1px solid #e5e7eb;
        }

        .sidebar-header {
            padding: 1rem 0 0rem 0rem;
            /* kiri lebih kecil supaya menempel kiri */
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }


        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            /* ubah dari center ke kiri */
            color: #575757ff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            width: 100%;
        }

        .sidebar-logo {
            width: 85px;
            /* bisa disesuaikan */
            height: 85px;
            object-fit: contain;
            display: inline-block;
            margin-right: -9px;
            /* beri jarak antara logo dan teks */
        }

        .sidebar-section {
            padding: 1.5rem 0 0.75rem 1.25rem;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu-item {
            margin: 0.125rem 0;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.625rem 1.25rem;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9375rem;
            font-weight: 500;
            border-radius: 0;
        }

        .sidebar-menu-link:hover {
            color: #2D4194;
            background: #f3f4f6;
        }

        .sidebar-menu-link.active {
            color: #2D4194;
            background: #eff6ff;
            border-left: 3px solid #2D4194;
        }

        .sidebar-menu-icon {
            font-size: 1.125rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            height: var(--navbar-height);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-welcome {
            font-size: 1rem;
            color: #1f2937;
        }

        .navbar-welcome strong {
            font-weight: 600;
            color: #2D4194;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-search {
            position: relative;
            width: 280px;
        }

        .navbar-search input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            background: #f9fafb;
        }

        .navbar-search input:focus {
            outline: none;
            border-color: #2D4194;
            background: white;
        }

        .navbar-search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
        }

        .navbar-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .navbar-btn:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .navbar-btn .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 16px;
            height: 16px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            font-size: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.375rem 0.75rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-profile:hover {
            background: #f3f4f6;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: linear-gradient(135deg, #2D4194 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.8125rem;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
        }

        /* Content Area */
        .content-wrapper {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }

        .stats-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .stats-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.75rem;
        }

        .stats-bullet {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .stats-bullet.primary {
            background: #2D4194;
        }

        .stats-bullet.success {
            background: #10b981;
        }

        .stats-bullet.warning {
            background: #f59e0b;
        }

        .stats-bullet.danger {
            background: #ef4444;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stats-icon.primary {
            background: #eff6ff;
            color: #2D4194;
        }

        .stats-icon.success {
            background: #d1fae5;
            color: #10b981;
        }

        .stats-icon.warning {
            background: #fef3c7;
            color: #f59e0b;
        }

        .stats-icon.danger {
            background: #fee2e2;
            color: #ef4444;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        /* Tabs */
        .custom-tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 1.5rem;
            background: #fafbfc;
        }

        .custom-tab {
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }

        .custom-tab:hover {
            color: #2D4194;
        }

        .custom-tab.active {
            color: #2D4194;
            border-bottom-color: #2D4194;
        }

        /* Table */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table thead th {
            background: #fafbfc;
            padding: 0.875rem 1.5rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            border-bottom: 1px solid #e5e7eb;
        }

        .custom-table tbody td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        .custom-table tbody tr:hover {
            background: #fafbfc;
        }

        /* Badges */
        .badge-code {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            background: #f3f4f6;
            color: #374151;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 500;
            font-family: 'Monaco', monospace;
        }

        .badge-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #f3f4f6;
            color: #6b7280;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .badge-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .badge-status.success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-status.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-status.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Pagination */
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #fafbfc;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .pagination {
            display: flex;
            gap: 0.25rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .page-item {
            display: flex;
        }

        .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #6b7280;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .page-link:hover {
            background: #f9fafb;
            color: #2D4194;
        }

        .page-item.active .page-link {
            background: #2D4194;
            color: white;
            border-color: #2D4194;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .main-footer {
            background: white;
            padding: 1.5rem 2rem;
            margin-top: 3rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-search {
                display: none;
            }

            .mobile-menu-btn {
                display: flex !important;
            }
        }

        .mobile-menu-btn {
            display: none;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Tombol Logout Merah */
        .sidebar-menu-link.logout {
            color: #dc2626;
            /* merah utama */
            font-weight: 600;
        }

        .sidebar-menu-link.logout:hover {
            background: #fee2e2;
            /* latar merah muda saat hover */
            color: #b91c1c;
            /* merah lebih gelap */
        }

        #logoutModal .modal-content {
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }

        #logoutModal .modal-content:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        #logoutModal .modal-header {
            padding: 1.5rem;
            background: white !important;
        }

        #logoutModal .modal-body {
            padding: 1rem 1.5rem;
            background: white !important;
        }

        #logoutModal .modal-footer {
            padding: 1rem 1.5rem;
            background: white !important;
        }

        #logoutModal .btn-outline-secondary {
            border-radius: 0.6rem;
            transition: all 0.2s;
        }

        #logoutModal .btn-outline-secondary:hover {
            background-color: #f3f4f6;
            transform: translateY(-1px);
        }

        #logoutModal .btn-danger {
            border-radius: 0.6rem;
            transition: all 0.2s;
        }

        #logoutModal .btn-danger:hover {
            background-color: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(185, 28, 28, 0.3);
        }

        .modal-content {
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modal-content:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            padding: 1rem 1.5rem;
        }

        .modal-body {
            padding: 1rem 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
        }

        .btn-outline-secondary {
            border-radius: 0.6rem;
            transition: all 0.2s;
        }

        .btn-outline-secondary:hover {
            background-color: #f3f4f6;
            transform: translateY(-1px);
        }

        .btn-danger {
            border-radius: 0.6rem;
            transition: all 0.2s;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(185, 28, 28, 0.3);
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="sidebar-logo">
                <span>ManagementHub</span>
            </a>

        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Menu Utama</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 sidebar-menu-icon"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('damage-reports.index') }}" class="sidebar-menu-link {{ request()->routeIs('damage-reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-text sidebar-menu-icon"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Management</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('items.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                        <i class="bi bi-hdd-rack sidebar-menu-icon"></i>
                        <span>Perangkat</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('categories.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-folder sidebar-menu-icon"></i>
                        <span>Kategori</span>
                    </a>
                </li>
                
                <!-- Tambahan untuk Room -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('rooms.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                        <i class="bi bi-door-open sidebar-menu-icon"></i>
                        <span>Ruangan</span>
                    </a>
                </li>
                
                <!-- Tambahan untuk Floor -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('floors.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('floors.*') ? 'active' : '' }}">
                        <i class="bi bi-building sidebar-menu-icon"></i>
                        <span>Lantai</span>
                    </a>
                </li>

                <!-- Tambahan untuk Building -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('building.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('building.*') ? 'active' : '' }}">
                        <i class="bi bi-bank sidebar-menu-icon"></i>
                        <span>Gedung</span>
                    </a>
                </li>

            </ul>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Pengaturan</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <!-- Tombol trigger modal logout -->
                    <button type="button" class="sidebar-menu-link logout w-100 text-start border-0 bg-transparent"
                        data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="bi bi-box-arrow-right sidebar-menu-icon"></i>
                        <span>Keluar</span>
                    </button>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- Header -->
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center" id="logoutModalLabel">
                        <i class="bi bi-box-arrow-right text-danger me-2 fs-4"></i>
                        Konfirmasi Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body text-secondary fs-6">
                    Apakah Anda yakin ingin keluar dari akun ini?
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold d-flex align-items-center">
                            <i class="bi bi-check-circle me-2"></i>Ya, Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <button class="navbar-btn mobile-menu-btn" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>

            <div class="navbar-welcome">
                Selamat datang <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>!
            </div>

            <div class="navbar-actions">
                <div class="navbar-search">
                    <form action="{{ route('items.search') }}" method="GET" class="d-flex w-100">
                        <i class="bi bi-search navbar-search-icon"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Pencarian">
                    </form>
                </div>

                <div class="user-profile">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'AD',0,2)) }}</div>
                    <div class="user-name d-none d-md-block">{{ Auth::user()->name ?? 'Admin' }}</div>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }
        </script>
                 @stack('scripts')
</body>

</html>