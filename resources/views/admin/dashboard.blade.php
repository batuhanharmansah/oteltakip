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

                                                @if($submission->photo_path)
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h6>Çekilen Fotoğraf</h6>
                                                        <img src="{{ asset('storage/' . $submission->photo_path) }}"
                                                             class="img-fluid rounded" alt="Görev Fotoğrafı"
                                                             style="max-height: 400px;">
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
