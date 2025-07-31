@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tasks me-2"></i>
                Checklist Yönetimi
            </h1>
            <a href="{{ route('admin.checklists.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Yeni Checklist
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tüm Checklist'ler</h5>
            </div>
            <div class="card-body">
                @if($checklists->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Başlık</th>
                                    <th>Oluşturan</th>
                                    <th>Madde Sayısı</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checklists as $checklist)
                                <tr>
                                    <td>{{ $checklist->title }}</td>
                                    <td>{{ $checklist->creator->full_name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $checklist->items->count() }}</span>
                                    </td>
                                    <td>{{ $checklist->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.checklists.edit', $checklist) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.checklists.destroy', $checklist) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu checklist\'i silmek istediğinizden emin misiniz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $checklists->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Henüz checklist bulunmuyor.</p>
                        <a href="{{ route('admin.checklists.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            İlk Checklist'i Oluştur
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
