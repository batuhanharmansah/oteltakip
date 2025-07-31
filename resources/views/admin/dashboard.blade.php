@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">
            <i class="fas fa-tachometer-alt me-2"></i>
            Admin Dashboard
        </h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Toplam Kullanıcı</h5>
                        <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Çalışanlar</h5>
                        <h2 class="mb-0">{{ $stats['total_employees'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Atanmış Görevler</h5>
                        <h2 class="mb-0">{{ $stats['total_assignments'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">QR Taramaları</h5>
                        <h2 class="mb-0">{{ $stats['total_qr_scans'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-qrcode fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tasks me-2"></i>
                    Son Atanmış Görevler
                </h5>
            </div>
            <div class="card-body">
                @if($recent_assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Çalışan</th>
                                    <th>Görev</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_assignments as $assignment)
                                <tr>
                                    <td>{{ $assignment->user->full_name }}</td>
                                    <td>{{ $assignment->checklist->title }}</td>
                                    <td>
                                        @if($assignment->active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Pasif</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Henüz atanmış görev bulunmuyor.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Son Görev Raporları
                </h5>
            </div>
            <div class="card-body">
                @if($recent_submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Çalışan</th>
                                    <th>Görev</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_submissions as $submission)
                                <tr>
                                    <td>{{ $submission->user->full_name }}</td>
                                    <td>{{ $submission->assignment->checklist->title }}</td>
                                    <td>{{ $submission->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Henüz görev raporu bulunmuyor.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-rocket me-2"></i>
                    Hızlı İşlemler
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Yeni Kullanıcı
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.checklists.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-plus me-2"></i>
                            Yeni Checklist
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-qrcode me-2"></i>
                            Yeni QR Kod
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.checklists.assign') }}" class="btn btn-warning w-100">
                            <i class="fas fa-tasks me-2"></i>
                            Görev Ata
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
