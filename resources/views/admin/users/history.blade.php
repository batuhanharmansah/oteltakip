@extends('layouts.modern')

@section('page-title', 'Kullanıcı Geçmişi')

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
                                                    <a href="{{ route('admin.submissions.show', $submission) }}"
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>


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
