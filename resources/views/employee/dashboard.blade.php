@extends('layouts.modern')

@section('page-title', 'Overview')

@section('content')
@if($activeAssignment)
<!-- Active Assignment Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-day me-2"></i>
                    Aktif Görevler
                </h5>
            </div>
            <div class="card-body">
                <h6 class="card-subtitle mb-3">{{ $activeAssignment->checklist->title }}</h6>
                <div class="row">
                    @foreach($activeAssignment->checklist->items as $item)
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span>{{ $item->content }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <a href="{{ route('employee.today-tasks') }}" class="btn btn-success">
                        <i class="fas fa-tasks me-2"></i>
                        Görevleri Tamamla
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<!-- No Active Assignment -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-secondary">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Aktif Görev
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">Aktif görev bulunmuyor.</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Stats Cards Row -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                <i class="fas fa-qrcode"></i>
            </div>
            <div class="stats-number">{{ $recentScans->count() }}</div>
            <div class="stats-label">QR Taramaları</div>
            <div class="stats-subtitle">Bu ay</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="stats-number">{{ $recentSubmissions->count() }}</div>
            <div class="stats-label">Tamamlanan Görev</div>
            <div class="stats-subtitle">Bu hafta</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stats-number">{{ $activeAssignment ? $activeAssignment->checklist->items->count() : 0 }}</div>
            <div class="stats-label">Aktif Görev Maddesi</div>
            <div class="stats-subtitle">Bugün</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-number">{{ $recentSubmissions->where('is_checked', false)->count() }}</div>
            <div class="stats-label">Bekleyen Görev</div>
            <div class="stats-subtitle">Devam ediyor</div>
        </div>
    </div>
</div>



<!-- Tables Row -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-qrcode me-2"></i>
                    Son QR Taramaları
                </h5>
            </div>
            <div class="card-body">
                @if($recentScans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Lokasyon</th>
                                    <th>Tip</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentScans as $scan)
                                <tr>
                                    <td>{{ $scan->qrCode->location }}</td>
                                    <td>
                                        @if($scan->qrCode->type == 'entry')
                                            <span class="badge bg-success">Giriş</span>
                                        @elseif($scan->qrCode->type == 'exit')
                                            <span class="badge bg-danger">Çıkış</span>
                                        @else
                                            <span class="badge bg-info">Görev</span>
                                        @endif
                                    </td>
                                    <td>{{ $scan->scanned_at->format('d.m.Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Henüz QR taraması bulunmuyor.</p>
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
                @if($recentSubmissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Görev</th>
                                    <th>Tarih</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubmissions as $submission)
                                <tr>
                                    <td>{{ $submission->assignment->checklist->title }}</td>
                                    <td>{{ $submission->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        @if($submission->is_checked)
                                            <span class="badge bg-success">Tamamlandı</span>
                                        @else
                                            <span class="badge bg-warning">Bekliyor</span>
                                        @endif
                                    </td>
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

<!-- Quick Actions -->
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
                        <a href="{{ route('employee.qr-scanner') }}" class="btn btn-primary w-100">
                            <i class="fas fa-qrcode me-2"></i>
                            QR Kod Oku
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('employee.today-tasks') }}" class="btn btn-success w-100">
                            <i class="fas fa-tasks me-2"></i>
                            Bugünkü Görevler
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('employee.task-history') }}" class="btn btn-info w-100">
                            <i class="fas fa-history me-2"></i>
                            Görev Geçmişi
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('employee.qr-history') }}" class="btn btn-warning w-100">
                            <i class="fas fa-qrcode me-2"></i>
                            QR Geçmişi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
