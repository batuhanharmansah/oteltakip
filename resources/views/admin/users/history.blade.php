@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    {{ $user->full_name }} - Kullanıcı Geçmişi
                </h5>
            </div>
            <div class="card-body">
                <!-- User Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ $user->submissions->count() }}</div>
                            <div class="stats-label">Toplam Görev Raporu</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ $user->qrScans->count() }}</div>
                            <div class="stats-label">Toplam QR Tarama</div>
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-user me-2"></i>
                                    Kullanıcı Bilgileri
                                </h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Ad Soyad:</strong> {{ $user->full_name }}</p>
                                <p><strong>E-posta:</strong> {{ $user->email }}</p>
                                <p><strong>Telefon:</strong> {{ $user->phone ?? 'Belirtilmemiş' }}</p>
                                <p><strong>Rol:</strong> 
                                    @if($user->role == 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-primary">Çalışan</span>
                                    @endif
                                </p>
                                <p><strong>Kayıt Tarihi:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    İletişim Bilgileri
                                </h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Acil Durum Kişisi:</strong> {{ $user->emergency_contact_name ?? 'Belirtilmemiş' }}</p>
                                <p><strong>Acil Durum Telefonu:</strong> {{ $user->emergency_contact_phone ?? 'Belirtilmemiş' }}</p>
                                <p><strong>Adres:</strong> {{ $user->address ?? 'Belirtilmemiş' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Submissions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Son Görev Raporları
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($user->submissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Görev</th>
                                            <th>Checklist</th>
                                            <th>Durum</th>
                                            <th>Fotoğraf</th>
                                            <th>Tarih</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->submissions->take(10) as $submission)
                                            <tr>
                                                <td>{{ $submission->item->content }}</td>
                                                <td>{{ $submission->assignment->checklist->title }}</td>
                                                <td>
                                                    @if($submission->is_checked)
                                                        <span class="badge bg-success">Tamamlandı</span>
                                                    @else
                                                        <span class="badge bg-warning">Devam Ediyor</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($submission->photo_path || $submission->photo_data)
                                                        <i class="fas fa-camera text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $submission->created_at->format('d.m.Y H:i') }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info" 
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
                                                            <h5 class="modal-title">Görev Detayları</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
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
                                                                <div class="col-md-6">
                                                                    <h6>Zaman Bilgileri</h6>
                                                                    <p><strong>Oluşturulma:</strong> {{ $submission->created_at->format('d.m.Y H:i:s') }}</p>
                                                                    @if($submission->completed_at)
                                                                        <p><strong>Tamamlanma:</strong> {{ $submission->completed_at->format('d.m.Y H:i:s') }}</p>
                                                                    @endif
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz görev raporu bulunmuyor.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent QR Scans -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-qrcode me-2"></i>
                            Son QR Taramaları
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($user->qrScans->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>QR Kod</th>
                                            <th>Konum</th>
                                            <th>İşlem</th>
                                            <th>Tarih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->qrScans->take(10) as $scan)
                                            <tr>
                                                <td>{{ $scan->qrCode->name }}</td>
                                                <td>{{ $scan->qrCode->location }}</td>
                                                <td>
                                                    <span class="badge bg-info">Giriş/Çıkış</span>
                                                </td>
                                                <td>{{ $scan->created_at->format('d.m.Y H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz QR taraması bulunmuyor.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Geri Dön
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-user-edit me-2"></i>
                        Kullanıcıyı Düzenle
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 