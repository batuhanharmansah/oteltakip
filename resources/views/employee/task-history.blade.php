@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Görev Geçmişi
                </h5>
            </div>
            <div class="card-body">
                @if($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Checklist</th>
                                    <th>Durum</th>
                                    <th>Tamamlanan Görevler</th>
                                    <th>Son Güncelleme</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    <tr>
                                        <td>
                                            <strong>{{ $assignment->checklist->title }}</strong>
                                            @if($assignment->checklist->description)
                                                <br><small class="text-muted">{{ $assignment->checklist->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($assignment->active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Pasif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $totalItems = $assignment->checklist->items->count();
                                                $completedItems = $assignment->submissions->where('is_checked', true)->count();
                                                $percentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $completedItems }}/{{ $totalItems }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($assignment->submissions->count() > 0)
                                                {{ $assignment->submissions->latest()->first()->updated_at->format('d.m.Y H:i') }}
                                            @else
                                                <span class="text-muted">Henüz rapor yok</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('employee.today-tasks') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>
                                                Görüntüle
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $assignments->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Henüz görev atanmamış</h5>
                        <p class="text-muted">Size atanmış görevler burada görünecek.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Submissions Card -->
@if($recentSubmissions->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Son Görev Raporları
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($recentSubmissions->take(6) as $submission)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $submission->item->content }}</h6>
                                        @if($submission->is_checked)
                                            <span class="badge bg-success">Tamamlandı</span>
                                        @else
                                            <span class="badge bg-warning">Devam Ediyor</span>
                                        @endif
                                    </div>
                                    <p class="card-text text-muted small">
                                        {{ $submission->assignment->checklist->title }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            {{ $submission->created_at->format('d.m.Y H:i') }}
                                        </small>
                                        @if($submission->photo_path || $submission->photo_data)
                                            <i class="fas fa-camera text-success"></i>
                                        @endif
                                    </div>
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
@endsection 