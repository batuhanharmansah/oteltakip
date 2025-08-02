<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Otel Takip Sistemi') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --sidebar-active: #e67e22;
            --main-bg: #ecf0f1;
            --card-bg: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #3498db;
            --primary-color: #e67e22;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 250px;
            background: var(--sidebar-bg);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .sidebar-user {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-info h6 {
            margin: 0;
            font-weight: 600;
        }

        .user-info small {
            color: rgba(255, 255, 255, 0.7);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 1rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.5px;
        }

        .nav-item {
            list-style: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary-color);
        }

        .nav-link.active {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: var(--sidebar-active);
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 0.9rem;
        }

        .nav-dropdown {
            padding-left: 2.5rem;
            background: rgba(0, 0, 0, 0.1);
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background: var(--main-bg);
        }

        .top-header {
            background: var(--card-bg);
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e9ecef;
            border-radius: 20px;
            background: #f8f9fa;
        }

        .header-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .header-icon:hover {
            background: var(--primary-color);
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .stats-card {
            text-align: center;
            padding: 1.5rem;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .stats-subtitle {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }



        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Tables */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: var(--text-secondary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 1rem;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <i class="fas fa-hotel me-2"></i>
                OTEL TAKİP
            </a>
        </div>

        <div class="sidebar-user">
            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <h6>{{ auth()->user()->full_name }}</h6>
                    <small>{{ auth()->user()->isAdmin() ? 'Admin' : 'Çalışan' }}</small>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="nav-section">
                        <div class="nav-section-title">Dashboard</div>
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-chart-bar"></i>
                                    Stats
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="nav-section">
                        <div class="nav-section-title">Yönetim</div>
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    Kullanıcılar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.checklists.index') }}" class="nav-link {{ request()->routeIs('admin.checklists.*') ? 'active' : '' }}">
                                    <i class="fas fa-tasks"></i>
                                    Görevler
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.qr-codes.index') }}" class="nav-link {{ request()->routeIs('admin.qr-codes.*') ? 'active' : '' }}">
                                    <i class="fas fa-qrcode"></i>
                                    QR Kodlar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.submissions.index') }}" class="nav-link {{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
                                    <i class="fas fa-clipboard-check"></i>
                                    Raporlar
                                </a>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="nav-section">
                        <div class="nav-section-title">Dashboard</div>
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Overview
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="nav-section">
                        <div class="nav-section-title">İşlemler</div>
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="{{ route('employee.qr-scanner') }}" class="nav-link {{ request()->routeIs('employee.qr-scanner') ? 'active' : '' }}">
                                    <i class="fas fa-qrcode"></i>
                                    QR Kod Oku
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.today-tasks') }}" class="nav-link {{ request()->routeIs('employee.today-tasks') ? 'active' : '' }}">
                                    <i class="fas fa-tasks"></i>
                                    Bugünkü Görevler
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.task-history') }}" class="nav-link {{ request()->routeIs('employee.task-history') ? 'active' : '' }}">
                                    <i class="fas fa-history"></i>
                                    Görev Geçmişi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.qr-history') }}" class="nav-link {{ request()->routeIs('employee.qr-history') ? 'active' : '' }}">
                                    <i class="fas fa-qrcode"></i>
                                    QR Geçmişi
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                <div class="nav-section">
                    <div class="nav-section-title">Hesap</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="fas fa-user"></i>
                                Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Çıkış
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Ara..." class="form-control">
                </div>
            </div>
            <div class="header-right">
                <a href="#" class="header-icon position-relative">
                    <i class="fas fa-chart-bar"></i>
                </a>
                <a href="#" class="header-icon position-relative">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </a>
                <a href="#" class="header-icon">
                    <i class="fas fa-cog"></i>
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Add fade-in animation to all cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
