@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Checklist Düzenle: {{ $checklist->title }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.checklists.update', $checklist) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading me-1"></i> Checklist Başlığı
                        </label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $checklist->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-1"></i> Açıklama
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $checklist->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-list-check me-1"></i> Checklist Maddeleri
                        </label>
                        
                        <div id="items-container">
                            @foreach($checklist->items as $index => $item)
                                <div class="item-row mb-3 p-3 border rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" 
                                                   name="items[{{ $index }}][content]" 
                                                   value="{{ $item->content }}" 
                                                   placeholder="Madde içeriği" required>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" name="items[{{ $index }}][order]">
                                                @for($i = 1; $i <= 20; $i++)
                                                    <option value="{{ $i }}" {{ $item->order == $i ? 'selected' : '' }}>
                                                        Sıra {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="btn btn-success" id="add-item">
                            <i class="fas fa-plus me-2"></i>
                            Madde Ekle
                        </button>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.checklists.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Geri Dön
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = {{ $checklist->items->count() }};

document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const itemRow = document.createElement('div');
    itemRow.className = 'item-row mb-3 p-3 border rounded bg-light';
    itemRow.innerHTML = `
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" 
                       name="items[${itemIndex}][content]" 
                       placeholder="Madde içeriği" required>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="items[${itemIndex}][order]">
                    ${Array.from({length: 20}, (_, i) => i + 1).map(num => 
                        `<option value="${num}">Sıra ${num}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(itemRow);
    itemIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.item-row').remove();
    }
});
</script>
@endpush
@endsection 