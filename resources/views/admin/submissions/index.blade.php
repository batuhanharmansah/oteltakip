@extends('layouts.modern')

@section('page-title', 'Görev Raporları')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-clipboard-check me-2"></i>
                Görev Raporları
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tüm Görev Raporları</h5>
            </div>
            <div class="card-body">
                @if($submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Çalışan</th>
                                    <th>Görev</th>
                                    <th>Maddeler</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $submission->user->full_name }}</strong><br>
                                            <small class="text-muted">{{ $submission->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $submission->assignment->checklist->title }}</strong><br>
                                        <small class="text-muted">{{ $submission->item->content }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $submission->item->content }}</span>
                                    </td>
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
                                        <div>
                                            <strong>{{ $submission->created_at->format('d.m.Y') }}</strong><br>
                                            <small class="text-muted">{{ $submission->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#submissionModal{{ $submission->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.users.history', $submission->user) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        </div>
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

                    <div class="d-flex justify-content-center">
                        {{ $submissions->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Henüz görev raporu bulunmuyor.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
