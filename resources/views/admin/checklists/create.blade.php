@extends('layouts.modern')

@section('page-title', 'Yeni Checklist')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>
                Yeni Checklist Oluştur
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
                <h5 class="card-title mb-0">Checklist Bilgileri</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.checklists.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Checklist Başlığı *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="Örn: Günlük Temizlik Görevleri" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Checklist Maddeleri *</label>
                        <div id="items-container">
                            <div class="item-row mb-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="items[]"
                                           placeholder="Madde içeriği" required>
                                    <button type="button" class="btn btn-outline-danger remove-item" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-item">
                            <i class="fas fa-plus me-1"></i>
                            Madde Ekle
                        </button>

                        @error('items')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.checklists.index') }}" class="btn btn-secondary me-2">İptal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Checklist Oluştur
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
                <p class="mb-2">Checklist oluşturduktan sonra çalışanlara atayabilirsiniz.</p>
                <p class="mb-2">En az bir madde eklemeniz gereklidir.</p>
                <p class="mb-0"><small class="text-muted">* işaretli alanlar zorunludur.</small></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('items-container');
    const addButton = document.getElementById('add-item');

    addButton.addEventListener('click', function() {
        const itemRow = document.createElement('div');
        itemRow.className = 'item-row mb-2';
        itemRow.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control" name="items[]" placeholder="Madde içeriği" required>
                <button type="button" class="btn btn-outline-danger remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(itemRow);
        updateRemoveButtons();
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            const itemRow = e.target.closest('.item-row');
            if (container.children.length > 1) {
                itemRow.remove();
                updateRemoveButtons();
            }
        }
    });

    function updateRemoveButtons() {
        const removeButtons = container.querySelectorAll('.remove-item');
        removeButtons.forEach(button => {
            if (container.children.length === 1) {
                button.style.display = 'none';
            } else {
                button.style.display = 'inline-block';
            }
        });
    }

    updateRemoveButtons();
});
</script>
@endpush
