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
            --sidebar-width: 260px;
            --navbar-height: 70px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }
        .sidebar {
            position: fixed;
            left: 0; top: 0;
            height: 100vh; width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-header {
            padding: 1.5rem;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: 700;
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: var(--primary-gradient);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }
        .sidebar-menu { list-style: none; padding: 1rem 0; }
        .sidebar-menu-item { margin: 0.25rem 0; }
        .sidebar-menu-link {
            display: flex; align-items: center; gap: 12px;
            padding: 0.875rem 1.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative; font-weight: 500;
        }
        .sidebar-menu-link:hover { color: white; background: rgba(255,255,255,0.05); }
        .sidebar-menu-link.active {
            color: white; background: rgba(102,126,234,0.15);
            border-left: 3px solid #667eea;
        }
        .sidebar-menu-link.active::before {
            content: ''; position: absolute; right: 0; top: 50%;
            transform: translateY(-50%);
            width: 4px; height: 60%; background: #667eea;
            border-radius: 4px 0 0 4px;
        }
        .sidebar-menu-icon { font-size: 1.25rem; width: 24px; text-align: center; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: all 0.3s ease; }
        .top-navbar {
            background: white; height: var(--navbar-height);
            padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 999;
        }
        .navbar-search { flex: 1; max-width: 500px; position: relative; }
        .navbar-search input {
            width: 100%; padding: 0.625rem 1rem 0.625rem 2.75rem;
            border: 2px solid #e2e8f0; border-radius: 12px;
            font-size: 0.9rem; background: #f8fafc;
        }
        .navbar-search input:focus {
            outline: none; border-color: #667eea; background: white;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .navbar-search-icon {
            position: absolute; left: 1rem; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; font-size: 1.1rem;
        }
        .navbar-actions { display: flex; align-items: center; gap: 1rem; }
        .navbar-btn {
            width: 42px; height: 42px; border-radius: 10px; border: none;
            background: #f8fafc; color: #64748b;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; position: relative;
        }
        .navbar-btn .badge {
            position: absolute; top: -4px; right: -4px;
            width: 18px; height: 18px;
            background: #ef4444; color: white;
            border-radius: 50%; font-size: 0.7rem;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid white;
        }
        .user-profile {
            display: flex; align-items: center; gap: 12px;
            padding: 0.5rem 1rem;
            background: #f8fafc;
            border-radius: 12px;
            margin-left: 1rem;
            cursor: pointer;
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--primary-gradient);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 0.9rem;
        }
        .content-wrapper { padding: 2rem; }
        .main-footer {
            background: white; padding: 1.5rem 2rem; margin-top: 3rem;
            border-top: 1px solid #e2e8f0; text-align: center;
            color: #64748b; font-size: 0.875rem;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .navbar-search { display: none; }
            .mobile-menu-btn { display: flex !important; }
        }
        .mobile-menu-btn { display: none; }
        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999; display: none; opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.active { display: block; opacity: 1; }
    </style>
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-gear-fill"></i>
                </div>
                <span>MaintenanceHub</span>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 sidebar-menu-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('items.index') }}" class="sidebar-menu-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                    <i class="bi bi-hdd-rack sidebar-menu-icon"></i>
                    <span>Perangkat</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link {{ request()->is('components*') ? 'active' : '' }}">
                    <i class="bi bi-cpu sidebar-menu-icon"></i>
                    <span>Komponen</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link {{ request()->is('maintenance*') ? 'active' : '' }}">
                    <i class="bi bi-tools sidebar-menu-icon"></i>
                    <span>Maintenance</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link {{ request()->is('history*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history sidebar-menu-icon"></i>
                    <span>Riwayat</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('categories.index') }}" class="sidebar-menu-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-folder sidebar-menu-icon"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('damage-reports.index') }}" class="sidebar-menu-link {{ request()->routeIs('damage-reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line sidebar-menu-icon"></i>
                    <span>Laporan Kerusakan</span>
                </a>
            </li>
            <li class="sidebar-menu-item" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="#" class="sidebar-menu-link {{ request()->is('settings*') ? 'active' : '' }}">
                    <i class="bi bi-gear sidebar-menu-icon"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-menu-link w-100 text-start border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right sidebar-menu-icon"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <button class="navbar-btn mobile-menu-btn" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>

            <!-- Form Search -->
            <div class="navbar-search">
                <form action="{{ route('items.search') }}" method="GET" class="d-flex w-100">
                    <i class="bi bi-search navbar-search-icon"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari perangkat, komponen, atau maintenance...">
                </form>
            </div>

            <div class="navbar-actions">
                <button class="navbar-btn" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <span class="badge">3</span>
                </button>
                <button class="navbar-btn" title="Pesan">
                    <i class="bi bi-chat-dots"></i>
                </button>
                <button class="navbar-btn" title="Pengaturan">
                    <i class="bi bi-gear"></i>
                </button>

                <!-- User Profile -->
                <div class="user-profile">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'JD',0,2)) }}</div>
                    <div class="user-info d-none d-md-block">
                        <div class="user-name">{{ Auth::user()->name ?? 'John Doe' }}</div>
                        
                    </div>
                    <i class="bi bi-chevron-down d-none d-md-block" style="color: #94a3b8; font-size: 0.8rem;"></i>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <p class="mb-0">© {{ date('Y') }} MaintenanceHub - Sistem Manajemen Maintenance Jaringan</p>
            <small class="text-muted">Version 2.0.1 | Developed with ❤️</small>
        </footer>
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
</body>
</html>
