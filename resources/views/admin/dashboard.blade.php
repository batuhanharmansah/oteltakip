@extends('layouts.modern')

@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards Row -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-number">{{ $stats['total_users'] }}</div>
            <div class="stats-label">Toplam Kullanıcı</div>
            <div class="stats-subtitle">Son güncelleme</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stats-number">{{ $stats['total_employees'] }}</div>
            <div class="stats-label">Çalışanlar</div>
            <div class="stats-subtitle">Aktif çalışan</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stats-number">{{ $stats['total_assignments'] }}</div>
            <div class="stats-label">Atanmış Görevler</div>
            <div class="stats-subtitle">Son saat</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                <i class="fas fa-qrcode"></i>
            </div>
            <div class="stats-number">{{ $stats['total_qr_scans'] }}</div>
            <div class="stats-label">QR Taramaları</div>
            <div class="stats-subtitle">Güncellendi</div>
        </div>
    </div>
</div>



<!-- Tables Row -->
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
                                    <th>Durum</th>
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
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_submissions as $submission)
                                <tr>
                                    <td>{{ $submission->user->full_name }}</td>
                                    <td>{{ $submission->assignment->checklist->title }}</td>
                                    <td>
                                        @if($submission->is_checked)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Tamamlandı
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Devam Ediyor
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info submission-detail-btn"
                                                data-submission-id="{{ $submission->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal for submission details -->
                                <div class="modal fade submission-detail-modal" id="submissionModal{{ $submission->id }}" tabindex="-1" data-submission-id="{{ $submission->id }}">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 95%; margin: 1rem auto;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-clipboard-check me-2"></i>
                                                    Görev Detayları
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-6">
                                                        <div class="card border-0 bg-light">
                                                            <div class="card-body p-3">
                                                                <h6 class="card-title mb-3">
                                                                    <i class="fas fa-user me-2"></i>
                                                                    Çalışan Bilgileri
                                                                </h6>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Ad Soyad:</small>
                                                                    <div class="fw-bold">{{ $submission->user->full_name }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">E-posta:</small>
                                                                    <div class="fw-bold">{{ $submission->user->email }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Telefon:</small>
                                                                    <div class="fw-bold">{{ $submission->user->phone ?? 'Belirtilmemiş' }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="card border-0 bg-light">
                                                            <div class="card-body p-3">
                                                                <h6 class="card-title mb-3">
                                                                    <i class="fas fa-tasks me-2"></i>
                                                                    Görev Bilgileri
                                                                </h6>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Checklist:</small>
                                                                    <div class="fw-bold">{{ $submission->assignment->checklist->title }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Madde:</small>
                                                                    <div class="fw-bold">{{ $submission->item->content }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Durum:</small>
                                                                    <div>
                                                                        @if($submission->is_checked)
                                                                            <span class="badge bg-success">Tamamlandı</span>
                                                                        @else
                                                                            <span class="badge bg-warning">Devam Ediyor</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($submission->notes)
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="card border-0 bg-light">
                                                            <div class="card-body p-3">
                                                                <h6 class="card-title mb-3">
                                                                    <i class="fas fa-sticky-note me-2"></i>
                                                                    Notlar
                                                                </h6>
                                                                <p class="mb-0">{{ $submission->notes }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($submission->photo_path || $submission->photo_data)
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="card border-0 bg-light">
                                                            <div class="card-body p-3">
                                                                <h6 class="card-title mb-3">
                                                                    <i class="fas fa-camera me-2"></i>
                                                                    Çekilen Fotoğraf
                                                                </h6>
                                                                @if($submission->photo_path && file_exists(public_path($submission->photo_path)))
                                                                    <img src="{{ asset($submission->photo_path) }}"
                                                                         class="img-fluid rounded" alt="Görev Fotoğrafı"
                                                                         style="max-height: 300px; width: 100%; object-fit: cover;">
                                                                @elseif($submission->photo_data)
                                                                    <img src="data:image/jpeg;base64,{{ substr($submission->photo_data, strpos($submission->photo_data, ',') + 1) }}"
                                                                         class="img-fluid rounded" alt="Görev Fotoğrafı"
                                                                         style="max-height: 300px; width: 100%; object-fit: cover;">
                                                                @else
                                                                    <div class="alert alert-warning mb-0">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                                        Fotoğraf bulunamadı
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="card border-0 bg-light">
                                                            <div class="card-body p-3">
                                                                <h6 class="card-title mb-3">
                                                                    <i class="fas fa-clock me-2"></i>
                                                                    Zaman Bilgileri
                                                                </h6>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6">
                                                                        <div class="mb-2">
                                                                            <small class="text-muted">Oluşturulma:</small>
                                                                            <div class="fw-bold">{{ $submission->created_at->format('d.m.Y H:i:s') }}</div>
                                                                        </div>
                                                                    </div>
                                                                    @if($submission->completed_at)
                                                                    <div class="col-12 col-md-6">
                                                                        <div class="mb-2">
                                                                            <small class="text-muted">Tamamlanma:</small>
                                                                            <div class="fw-bold">{{ $submission->completed_at->format('d.m.Y H:i:s') }}</div>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
