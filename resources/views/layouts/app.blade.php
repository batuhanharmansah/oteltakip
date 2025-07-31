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

        @stack('styles')
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
                                    <i class="fas fa-user me-1"></i> {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-1"></i> Çıkış
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @else
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Giriş</a>
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
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
