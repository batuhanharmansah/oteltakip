@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tasks me-2"></i>
                Görev Ata
            </h1>
            <a href="{{ route('admin.checklists.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Geri Dön
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Görev Atama Formu
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.checklists.assign.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="checklist_id" class="form-label">Checklist Seçin *</label>
                        <select class="form-select @error('checklist_id') is-invalid @enderror"
                                id="checklist_id" name="checklist_id" required>
                            <option value="">Checklist Seçin</option>
                            @foreach($checklists as $checklist)
                                <option value="{{ $checklist->id }}" {{ old('checklist_id') == $checklist->id ? 'selected' : '' }}>
                                    {{ $checklist->title }} ({{ $checklist->items->count() }} madde)
                                </option>
                            @endforeach
                        </select>
                        @error('checklist_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Çalışan Seçin *</label>
                        <div class="row">
                            @foreach($employees as $employee)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="user_ids[]" value="{{ $employee->id }}"
                                               id="user_{{ $employee->id }}">
                                        <label class="form-check-label" for="user_{{ $employee->id }}">
                                            <strong>{{ $employee->full_name }}</strong><br>
                                            <small class="text-muted">{{ $employee->email }}</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('user_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                                                            <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Sürekli Görev Sistemi</h6>
                        <p class="mb-0">Atanan görevler sürekli aktif olacak. Çalışanlar her gün bu görevleri tamamlayıp fotoğraf çekerek rapor verecek.</p>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.checklists.index') }}" class="btn btn-secondary me-2">İptal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Görevleri Ata
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Bilgi
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Görev Atama Kuralları</h6>
                    <ul class="mb-0">
                        <li>Birden fazla çalışana aynı görev atanabilir</li>
                        <li>Aynı çalışana aynı gün için tekrar atama yapılmaz</li>
                        <li>Geçmiş tarihlere görev atanamaz</li>
                        <li>Atanan görevler çalışanların dashboard'unda görünür</li>
                    </ul>
                </div>

                <div class="mt-3">
                    <h6>Mevcut İstatistikler</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-0">{{ $checklists->count() }}</h4>
                                <small class="text-muted">Checklist</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-0">{{ $employees->count() }}</h4>
                                <small class="text-muted">Çalışan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
