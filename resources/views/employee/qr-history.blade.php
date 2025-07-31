@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-qrcode me-2"></i>
                    QR Kod Geçmişi
                </h5>
            </div>
            <div class="card-body">
                @if($qrScans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>QR Kod</th>
                                    <th>Konum</th>
                                    <th>Tarih & Saat</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($qrScans as $scan)
                                    <tr>
                                        <td>
                                            <strong>{{ $scan->qrCode->name }}</strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                            {{ $scan->qrCode->location }}
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $scan->created_at->format('d.m.Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $scan->created_at->format('H:i:s') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="fas fa-sign-in-alt me-1"></i>
                                                Giriş/Çıkış
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $qrScans->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-qrcode fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Henüz QR taraması yapılmamış</h5>
                        <p class="text-muted">QR kod taramalarınız burada görünecek.</p>
                        <a href="{{ route('employee.qr-scanner') }}" class="btn btn-primary">
                            <i class="fas fa-qrcode me-2"></i>
                            QR Kod Tara
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
@if($qrScans->count() > 0)
<div class="row mt-4">
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-number">{{ $qrScans->count() }}</div>
            <div class="stats-label">Toplam Tarama</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-number">{{ $qrScans->unique('qr_code_id')->count() }}</div>
            <div class="stats-label">Farklı QR Kod</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-number">{{ $qrScans->where('created_at', '>=', now()->startOfDay())->count() }}</div>
            <div class="stats-label">Bugünkü Tarama</div>
        </div>
    </div>
</div>

<!-- Recent Activity Timeline -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Son Aktiviteler
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($qrScans->take(10) as $scan)
                        <div class="timeline-item d-flex mb-3">
                            <div class="timeline-marker bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-qrcode text-white"></i>
                            </div>
                            <div class="timeline-content flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $scan->qrCode->name }}</h6>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $scan->qrCode->location }}
                                        </p>
                                    </div>
                                    <small class="text-muted">
                                        {{ $scan->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.timeline-item {
    position: relative;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    bottom: -20px;
    width: 2px;
    background: #e2e8f0;
}

.timeline-marker {
    flex-shrink: 0;
}
</style>
@endsection 