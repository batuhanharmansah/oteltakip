@extends('layouts.modern')

@section('page-title', 'Görev Detayları')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Görev Detayları
                </h1>
                <p class="text-muted mb-0">Görev tamamlama detayları ve fotoğraflar</p>
            </div>
            <div class="col-auto">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Geri Dön
                </a>
            </div>
        </div>
    </div>

    <!-- Submission Details -->
    <div class="row">
        <div class="col-md-8">
            <!-- Main Content -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Görev Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-muted">Checklist:</label>
                                <div class="fw-bold">{{ $submission->assignment->checklist->title }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-muted">Görev Maddesi:</label>
                                <div class="fw-bold">{{ $submission->item->content }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-muted">Durum:</label>
                                <div>
                                    @if($submission->is_checked)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Tamamlandı
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>
                                            Devam Ediyor
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-muted">Tamamlanma Tarihi:</label>
                                <div class="fw-bold">
                                    {{ $submission->completed_at ? $submission->completed_at->format('d.m.Y H:i') : 'Henüz tamamlanmadı' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($submission->notes)
                    <div class="mt-4">
                        <label class="form-label text-muted">
                            <i class="fas fa-sticky-note me-1"></i>
                            Notlar:
                        </label>
                        <div class="p-3 bg-light rounded">
                            {{ $submission->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Photo Section -->
            @if($submission->photo_path || $submission->photo_data)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-camera me-2"></i>
                        Çekilen Fotoğraf
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        @if($submission->photo_path && file_exists(public_path($submission->photo_path)))
                            <img src="{{ asset($submission->photo_path) }}"
                                 class="img-fluid rounded shadow" 
                                 alt="Görev Fotoğrafı"
                                 style="max-height: 500px; width: 100%; object-fit: contain;">
                        @elseif($submission->photo_data)
                            <img src="data:image/jpeg;base64,{{ substr($submission->photo_data, strpos($submission->photo_data, ',') + 1) }}"
                                 class="img-fluid rounded shadow" 
                                 alt="Görev Fotoğrafı"
                                 style="max-height: 500px; width: 100%; object-fit: contain;">
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Employee Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Çalışan Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            <div class="avatar-title bg-primary rounded-circle">
                                {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $submission->user->full_name }}</h6>
                            <small class="text-muted">{{ $submission->user->email }}</small>
                        </div>
                    </div>
                    
                    <div class="info-group">
                        <label class="form-label text-muted">Telefon:</label>
                        <div class="fw-bold">{{ $submission->user->phone ?? 'Belirtilmemiş' }}</div>
                    </div>
                    
                    <div class="info-group">
                        <label class="form-label text-muted">Vardiya:</label>
                        <div>
                            <span class="badge bg-{{ $submission->user->shift_type == 'day' ? 'warning' : 'dark' }}">
                                {{ $submission->user->shift_type_text }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-group">
                        <label class="form-label text-muted">Çalışma Saatleri:</label>
                        <div class="fw-bold">{{ $submission->user->formatted_start_time }} - {{ $submission->user->formatted_end_time }}</div>
                    </div>
                </div>
            </div>

            <!-- Assignment Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        Atama Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label class="form-label text-muted">Atama Tarihi:</label>
                        <div class="fw-bold">{{ $submission->assignment->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    
                    <div class="info-group">
                        <label class="form-label text-muted">Durum:</label>
                        <div>
                            @if($submission->assignment->active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-group">
                        <label class="form-label text-muted">Bitiş Tarihi:</label>
                        <div class="fw-bold">{{ $submission->assignment->due_date ? $submission->assignment->due_date->format('d.m.Y') : 'Belirtilmemiş' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-group {
    margin-bottom: 1rem;
}

.info-group:last-child {
    margin-bottom: 0;
}

.avatar-lg {
    width: 60px;
    height: 60px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
}
</style>
@endsection
