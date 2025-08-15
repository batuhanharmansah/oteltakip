@extends('layouts.modern')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-clock me-2"></i>
                    Vardiya Raporları
                </h1>
                <p class="text-muted mb-0">Çalışanların giriş-çıkış ve çalışma saatleri takibi</p>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.shift-reports.weekly') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-week me-1"></i>
                        Haftalık Rapor
                    </a>
                    <a href="{{ route('admin.shift-reports.monthly') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Aylık Rapor
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="date" class="form-label">Tarih</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                </div>
                <div class="col-md-3">
                    <label for="shift_type" class="form-label">Vardiya</label>
                    <select class="form-select" id="shift_type" name="shift_type">
                        <option value="all" {{ $shiftType == 'all' ? 'selected' : '' }}>Tüm Vardiyalar</option>
                        <option value="day" {{ $shiftType == 'day' ? 'selected' : '' }}>Gündüz Vardiyası</option>
                        <option value="night" {{ $shiftType == 'night' ? 'selected' : '' }}>Gece Vardiyası</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>
                        Filtrele
                    </button>
                    <a href="{{ route('admin.shift-reports.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>
                        Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number">{{ count($reports) }}</div>
                <div class="stats-label">Toplam Çalışan</div>
                <div class="stats-subtitle">{{ $date }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number">{{ collect($reports)->where('work_hours.total_hours', '>', 0)->count() }}</div>
                <div class="stats-label">Çalışan</div>
                <div class="stats-subtitle">Giriş yapan</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number">{{ collect($reports)->where('work_hours.is_late', true)->count() }}</div>
                <div class="stats-label">Geç Gelen</div>
                <div class="stats-subtitle">Vardiya başlangıcı</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <i class="fas fa-hourglass-end"></i>
                </div>
                <div class="stats-number">{{ collect($reports)->where('work_hours.is_early_leave', true)->count() }}</div>
                <div class="stats-label">Erken Çıkan</div>
                <div class="stats-subtitle">Vardiya bitişi</div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                Günlük Vardiya Raporu
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Çalışan</th>
                            <th>Vardiya</th>
                            <th>Beklenen Saatler</th>
                            <th>Giriş Saati</th>
                            <th>Çıkış Saati</th>
                            <th>Çalışma Süresi</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-primary rounded-circle">
                                            {{ strtoupper(substr($report['user']->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $report['user']->full_name }}</h6>
                                        <small class="text-muted">{{ $report['user']->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $report['user']->shift_type == 'day' ? 'warning' : 'dark' }}">
                                    {{ $report['user']->shift_type_text }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $report['user']->formatted_start_time }} - {{ $report['user']->formatted_end_time }}
                                </small>
                            </td>
                            <td>
                                @if($report['work_hours']['check_in'])
                                    <span class="text-success">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        {{ $report['work_hours']['check_in']->format('H:i') }}
                                    </span>
                                    @if($report['work_hours']['is_late'])
                                        <br><small class="text-danger">
                                            {{ $report['work_hours']['late_minutes'] }} dk geç
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($report['work_hours']['check_out'])
                                    <span class="text-info">
                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        {{ $report['work_hours']['check_out']->format('H:i') }}
                                    </span>
                                    @if($report['work_hours']['is_early_leave'])
                                        <br><small class="text-warning">
                                            {{ $report['work_hours']['early_leave_minutes'] }} dk erken
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($report['work_hours']['total_hours'] > 0)
                                    <span class="fw-bold text-primary">
                                        {{ $report['work_hours']['total_hours'] }} saat
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($report['work_hours']['total_hours'] > 0)
                                    @if($report['work_hours']['is_late'] && $report['work_hours']['is_early_leave'])
                                        <span class="badge bg-danger">Geç + Erken</span>
                                    @elseif($report['work_hours']['is_late'])
                                        <span class="badge bg-warning">Geç Giriş</span>
                                    @elseif($report['work_hours']['is_early_leave'])
                                        <span class="badge bg-info">Erken Çıkış</span>
                                    @else
                                        <span class="badge bg-success">Normal</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Giriş Yok</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.shift-reports.user-detail', $report['user']) }}?start_date={{ $date }}&end_date={{ $date }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>
                                    Detay
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <p>Seçilen tarih için veri bulunamadı.</p>
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
