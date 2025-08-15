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
                                            <a href="{{ route('admin.submissions.show', $submission) }}"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.history', $submission->user) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>


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
