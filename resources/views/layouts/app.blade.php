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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            :root {
                --primary-color: #2563eb;
                --primary-dark: #1d4ed8;
                --secondary-color: #64748b;
                --success-color: #059669;
                --warning-color: #d97706;
                --danger-color: #dc2626;
                --info-color: #0891b2;
                --light-color: #f8fafc;
                --dark-color: #1e293b;
                --glass-bg: rgba(255, 255, 255, 0.1);
                --glass-border: rgba(255, 255, 255, 0.2);
            }

            * {
                font-family: 'Inter', sans-serif;
            }

            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: var(--dark-color);
            }

            .navbar {
                background: rgba(37, 99, 235, 0.95) !important;
                backdrop-filter: blur(10px);
                border-bottom: 1px solid var(--glass-border);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 1.5rem;
            }

            .nav-link {
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .nav-link:hover {
                transform: translateY(-2px);
                color: #fff !important;
            }

            .dropdown-menu {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid var(--glass-border);
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .dropdown-item:hover {
                background: var(--primary-color);
                color: white;
                transform: translateX(5px);
            }

            .card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid var(--glass-border);
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            }

            .card-header {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border-radius: 16px 16px 0 0 !important;
                border: none;
                font-weight: 600;
            }

            .btn {
                border-radius: 12px;
                font-weight: 500;
                transition: all 0.3s ease;
                border: none;
                padding: 0.75rem 1.5rem;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            }

            .btn-success {
                background: linear-gradient(135deg, var(--success-color), #047857);
            }

            .btn-warning {
                background: linear-gradient(135deg, var(--warning-color), #b45309);
            }

            .btn-danger {
                background: linear-gradient(135deg, var(--danger-color), #b91c1c);
            }

            .btn-info {
                background: linear-gradient(135deg, var(--info-color), #0e7490);
            }

            .table {
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .table thead th {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border: none;
                font-weight: 600;
            }

            .badge {
                border-radius: 8px;
                font-weight: 500;
                padding: 0.5rem 0.75rem;
            }

            .alert {
                border-radius: 12px;
                border: none;
                backdrop-filter: blur(10px);
            }

            .modal-content {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid var(--glass-border);
                border-radius: 16px;
            }

            .form-control, .form-select {
                border-radius: 12px;
                border: 1px solid #e2e8f0;
                transition: all 0.3s ease;
            }

            .form-control:focus, .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
            }

            .pagination .page-link {
                border-radius: 8px;
                margin: 0 2px;
                border: none;
                color: var(--primary-color);
            }

            .pagination .page-item.active .page-link {
                background: var(--primary-color);
                border-color: var(--primary-color);
            }

            /* Animation classes */
            .fade-in {
                animation: fadeIn 0.5s ease-in;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .slide-in {
                animation: slideIn 0.3s ease-out;
            }

            @keyframes slideIn {
                from { transform: translateX(-20px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }

            /* Stats cards */
            .stats-card {
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
                border-radius: 16px;
                padding: 1.5rem;
                text-align: center;
                transition: all 0.3s ease;
            }

            .stats-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            }

            .stats-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary-color);
            }

            .stats-label {
                color: var(--secondary-color);
                font-weight: 500;
                margin-top: 0.5rem;
            }

            /* Responsive improvements */
            @media (max-width: 768px) {
                .card {
                    margin-bottom: 1rem;
                }
                
                .stats-card {
                    margin-bottom: 1rem;
                }
            }
        </style>

        @stack('styles')
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="fas fa-hotel me-2"></i>
                    Otel Takip Sistemi
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    @auth
                        <ul class="navbar-nav me-auto">
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-users me-1"></i> Kullanıcılar
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Tüm Kullanıcılar</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.users.create') }}">Yeni Kullanıcı</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-tasks me-1"></i> Görevler
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.checklists.index') }}">Checklist'ler</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.checklists.create') }}">Yeni Checklist</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.checklists.assign') }}">Görev Ata</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.checklists.assignments') }}">Atanmış Görevler</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-qrcode me-1"></i> QR Kodlar
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.qr-codes.index') }}">Tüm QR Kodlar</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.qr-codes.create') }}">Yeni QR Kod</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.qr-codes.history') }}">QR Geçmişi</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.submissions.index') }}">
                                        <i class="fas fa-clipboard-check me-1"></i> Görev Raporları
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employee.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employee.qr-scanner') }}">
                                        <i class="fas fa-qrcode me-1"></i> QR Kod Oku
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employee.today-tasks') }}">
                                        <i class="fas fa-tasks me-1"></i> Bugünkü Görevler
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employee.task-history') }}">
                                        <i class="fas fa-history me-1"></i> Görev Geçmişi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employee.qr-history') }}">
                                        <i class="fas fa-qrcode me-1"></i> QR Geçmişi
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i> Profil
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i> Çıkış
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
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
        </main>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
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
