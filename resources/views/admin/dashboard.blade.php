@extends('layouts.modern')

@section('page-title', 'Overview')

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

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Toplam Kazanç</h5>
                    <span class="badge bg-success">+18%</span>
                </div>
                <h3 class="mb-2">₺34,657</h3>
                <p class="text-muted small mb-3">SON ON ÇEYREKTE TOPLAM KAZANÇ</p>
                <div style="height: 100px; background: linear-gradient(90deg, #27ae60, #2ecc71); border-radius: 8px; position: relative;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 60%; background: rgba(255,255,255,0.2); border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Toplam Abonelik</h5>
                    <span class="badge bg-danger">-14%</span>
                </div>
                <h3 class="mb-2">169</h3>
                <p class="text-muted small mb-3">SON 7 GÜNDE TOPLAM ABONELİK</p>
                <div style="height: 100px; background: linear-gradient(90deg, #f39c12, #e67e22); border-radius: 8px; position: relative;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40%; background: rgba(255,255,255,0.2); border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Toplam İndirme</h5>
                    <span class="badge bg-warning">-51%</span>
                </div>
                <h3 class="mb-2">8,960</h3>
                <p class="text-muted small mb-3">SON 6 YILDA TOPLAM İNDİRME</p>
                <div style="height: 100px; background: linear-gradient(90deg, #9b59b6, #8e44ad); border-radius: 8px; position: relative;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 80%; background: rgba(255,255,255,0.2); border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Cards Row -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card progress-card">
            <h6 class="card-title">Dashboard - Aylık satış hedefi</h6>
            <div class="progress-circle" style="background: conic-gradient(#3498db 0deg 252deg, #e9ecef 252deg 360deg);">
                <div class="progress-text">70%</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card progress-card">
            <h6 class="card-title">Siparişler - Tamamlandı</h6>
            <div class="progress-circle" style="background: conic-gradient(#27ae60 0deg 122deg, #e9ecef 122deg 360deg);">
                <div class="progress-text">34%</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card progress-card">
            <h6 class="card-title">Yeni Ziyaretçiler - Toplam sayıdan</h6>
            <div class="progress-circle" style="background: conic-gradient(#f39c12 0deg 223deg, #e9ecef 223deg 360deg);">
                <div class="progress-text">62%</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card progress-card">
            <h6 class="card-title">Abonelikler - Aylık bülten</h6>
            <div class="progress-circle" style="background: conic-gradient(#e67e22 0deg 36deg, #e9ecef 36deg 360deg);">
                <div class="progress-text">10%</div>
            </div>
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
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#submissionModal{{ $submission->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal for submission details -->
                                <div class="modal fade" id="submissionModal{{ $submission->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Görev Detayları
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Çalışan Bilgileri</h6>
                                                        <p><strong>Ad Soyad:</strong> {{ $submission->user->full_name }}</p>
                                                        <p><strong>E-posta:</strong> {{ $submission->user->email }}</p>
                                                        <p><strong>Telefon:</strong> {{ $submission->user->phone ?? 'Belirtilmemiş' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Görev Bilgileri</h6>
                                                        <p><strong>Checklist:</strong> {{ $submission->assignment->checklist->title }}</p>
                                                        <p><strong>Madde:</strong> {{ $submission->item->content }}</p>
                                                        <p><strong>Durum:</strong>
                                                            @if($submission->is_checked)
                                                                <span class="badge bg-success">Tamamlandı</span>
                                                            @else
                                                                <span class="badge bg-warning">Devam Ediyor</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                @if($submission->notes)
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h6>Notlar</h6>
                                                        <p class="border rounded p-3 bg-light">{{ $submission->notes }}</p>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($submission->photo_path || $submission->photo_data)
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h6>Çekilen Fotoğraf</h6>
                                                        @if($submission->photo_path && file_exists(public_path($submission->photo_path)))
                                                            <img src="{{ asset($submission->photo_path) }}"
                                                                 class="img-fluid rounded" alt="Görev Fotoğrafı"
                                                                 style="max-height: 400px;">
                                                        @elseif($submission->photo_data)
                                                            <img src="data:image/jpeg;base64,{{ substr($submission->photo_data, strpos($submission->photo_data, ',') + 1) }}"
                                                                 class="img-fluid rounded" alt="Görev Fotoğrafı"
                                                                 style="max-height: 400px;">
                                                        @else
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Fotoğraf bulunamadı
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <h6>Zaman Bilgileri</h6>
                                                        <p><strong>Oluşturulma:</strong> {{ $submission->created_at->format('d.m.Y H:i:s') }}</p>
                                                        @if($submission->completed_at)
                                                            <p><strong>Tamamlanma:</strong> {{ $submission->completed_at->format('d.m.Y H:i:s') }}</p>
                                                        @endif
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
