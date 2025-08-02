@extends('layouts.modern')

@section('page-title', 'Atanmış Görevler')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-clipboard-list me-2"></i>
                Atanmış Görevler
            </h1>
            <a href="{{ route('admin.checklists.assign') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Yeni Görev Ata
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tüm Atanmış Görevler</h5>
            </div>
            <div class="card-body">
                @if($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Çalışan</th>
                                    <th>Görev</th>
                                    <th>Tarih</th>
                                    <th>Durum</th>
                                    <th>Madde Sayısı</th>
                                    <th>Tamamlanan</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $assignment->user->full_name }}</strong><br>
                                            <small class="text-muted">{{ $assignment->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $assignment->checklist->title }}</strong><br>
                                            <small class="text-muted">{{ $assignment->checklist->creator->full_name }} tarafından oluşturuldu</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($assignment->active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Pasif</span>
                                        @endif
                                        <small class="text-muted d-block">{{ $assignment->created_at->format('d.m.Y') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $totalItems = $assignment->checklist->items->count();
                                            $completedItems = $assignment->submissions->where('is_checked', true)->count();
                                            $percentage = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
                                        @endphp

                                        @if($percentage == 100)
                                            <span class="badge bg-success">Tamamlandı</span>
                                        @elseif($percentage > 0)
                                            <span class="badge bg-warning">Kısmi</span>
                                        @else
                                            <span class="badge bg-secondary">Bekliyor</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $totalItems }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar" role="progressbar"
                                                     style="width: {{ $percentage }}%"
                                                     aria-valuenow="{{ $percentage }}"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $completedItems }}/{{ $totalItems }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#assignmentModal{{ $assignment->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($assignment->date->isToday() || $assignment->date->isFuture())
                                                <a href="{{ route('admin.checklists.edit', $assignment->checklist) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal for assignment details -->
                                <div class="modal fade" id="assignmentModal{{ $assignment->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    {{ $assignment->checklist->title }} - {{ $assignment->user->full_name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Çalışan:</strong> {{ $assignment->user->full_name }}<br>
                                                        <strong>Tarih:</strong> {{ $assignment->date->format('d.m.Y') }}<br>
                                                        <strong>Durum:</strong>
                                                        @if($percentage == 100)
                                                            <span class="badge bg-success">Tamamlandı</span>
                                                        @elseif($percentage > 0)
                                                            <span class="badge bg-warning">Kısmi</span>
                                                        @else
                                                            <span class="badge bg-secondary">Bekliyor</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Toplam Madde:</strong> {{ $totalItems }}<br>
                                                        <strong>Tamamlanan:</strong> {{ $completedItems }}<br>
                                                        <strong>İlerleme:</strong> %{{ number_format($percentage, 1) }}
                                                    </div>
                                                </div>

                                                <h6>Görev Maddeleri:</h6>
                                                <div class="list-group">
                                                    @foreach($assignment->checklist->items as $item)
                                                        @php
                                                            $submission = $assignment->submissions->where('item_id', $item->id)->first();
                                                        @endphp
                                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span>{{ $item->content }}</span>
                                                            @if($submission && $submission->is_checked)
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check me-1"></i>Tamamlandı
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">
                                                                    <i class="fas fa-clock me-1"></i>Bekliyor
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $assignments->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Henüz atanmış görev bulunmuyor.</p>
                        <a href="{{ route('admin.checklists.assign') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            İlk Görevi Ata
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
