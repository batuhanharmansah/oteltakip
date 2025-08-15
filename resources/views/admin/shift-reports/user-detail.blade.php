@extends('layouts.modern')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-user-clock me-2"></i>
                    Çalışan Detay Raporu
                </h1>
                <p class="text-muted mb-0">{{ $user->full_name }} - Vardiya Takibi</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.shift-reports.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Geri Dön
                </a>
            </div>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg me-4">
                            <div class="avatar-title bg-primary rounded-circle">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $user->full_name }}</h4>
                            <p class="text-muted mb-2">{{ $user->email }}</p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-{{ $user->shift_type == 'day' ? 'warning' : 'dark' }} fs-6">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $user->shift_type_text }} Vardiyası
                                </span>
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-time me-1"></i>
                                    {{ $user->formatted_start_time }} - {{ $user->formatted_end_time }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.shift-reports.weekly') }}?user_id={{ $user->id }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-week me-1"></i>
                            Haftalık
                        </a>
                        <a href="{{ route('admin.shift-reports.monthly') }}?user_id={{ $user->id }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Aylık
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>
                        Filtrele
                    </button>
                    <a href="{{ route('admin.shift-reports.user-detail', $user) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>
                        Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-number">{{ $stats['total_work_days'] }}</div>
                <div class="stats-label">Çalışma Günü</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number">{{ $stats['total_work_hours'] }}</div>
                <div class="stats-label">Toplam Saat</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-number">{{ $stats['average_work_hours'] }}</div>
                <div class="stats-label">Ortalama Saat</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-number">{{ $stats['total_late_days'] }}</div>
                <div class="stats-label">Geç Gelen</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                    <i class="fas fa-hourglass-end"></i>
                </div>
                <div class="stats-number">{{ $stats['total_early_leave_days'] }}</div>
                <div class="stats-label">Erken Çıkan</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #1abc9c, #16a085);">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stats-number">{{ $stats['attendance_rate'] }}%</div>
                <div class="stats-label">Katılım Oranı</div>
            </div>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                Detaylı Çalışma Raporu
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Gün</th>
                            <th>Giriş Saati</th>
                            <th>Çıkış Saati</th>
                            <th>Çalışma Süresi</th>
                            <th>Durum</th>
                            <th>QR Taramaları</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dates as $dateData)
                        <tr class="{{ $dateData['work_hours']['total_hours'] > 0 ? 'table-success' : '' }}">
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($dateData['date'])->format('d.m.Y') }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $dateData['day_name'] }}</span>
                            </td>
                            <td>
                                @if($dateData['work_hours']['check_in'])
                                    <span class="text-success">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        {{ $dateData['work_hours']['check_in']->format('H:i') }}
                                    </span>
                                    @if($dateData['work_hours']['is_late'])
                                        <br><small class="text-danger">
                                            {{ $dateData['work_hours']['late_minutes'] }} dk geç
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($dateData['work_hours']['check_out'])
                                    <span class="text-info">
                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        {{ $dateData['work_hours']['check_out']->format('H:i') }}
                                    </span>
                                    @if($dateData['work_hours']['is_early_leave'])
                                        <br><small class="text-warning">
                                            {{ $dateData['work_hours']['early_leave_minutes'] }} dk erken
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($dateData['work_hours']['total_hours'] > 0)
                                    <span class="fw-bold text-primary">
                                        {{ $dateData['work_hours']['total_hours'] }} saat
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($dateData['work_hours']['total_hours'] > 0)
                                    @if($dateData['work_hours']['is_late'] && $dateData['work_hours']['is_early_leave'])
                                        <span class="badge bg-danger">Geç + Erken</span>
                                    @elseif($dateData['work_hours']['is_late'])
                                        <span class="badge bg-warning">Geç Giriş</span>
                                    @elseif($dateData['work_hours']['is_early_leave'])
                                        <span class="badge bg-info">Erken Çıkış</span>
                                    @else
                                        <span class="badge bg-success">Normal</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Giriş Yok</span>
                                @endif
                            </td>
                            <td>
                                @if($dateData['total_scans'] > 0)
                                    <span class="badge bg-primary">{{ $dateData['total_scans'] }} tarama</span>
                                    <br>
                                    <small class="text-muted">
                                        @foreach($dateData['scans'] as $scan)
                                            {{ $scan->scanned_at->format('H:i') }}
                                            @if(!$loop->last), @endif
                                        @endforeach
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <p>Seçilen tarih aralığı için veri bulunamadı.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
