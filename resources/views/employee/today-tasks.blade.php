@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tasks me-2"></i>
                Aktif Görevler
            </h1>
            <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Dashboard'a Dön
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    {{ $assignment->checklist->title }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('employee.submissions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                    <div class="alert alert-info">
                        <h6><i class="fas fa-camera me-2"></i>Fotoğraf Çekme Kuralları</h6>
                        <ul class="mb-0">
                            <li>Her görev maddesi için fotoğraf çekmeniz gerekiyor</li>
                            <li>Fotoğraf çekmeden görev tamamlanamaz</li>
                            <li>Galeriden fotoğraf yükleme özelliği yoktur</li>
                            <li>Fotoğraflar otomatik olarak sisteme kaydedilir</li>
                        </ul>
                    </div>

                    @foreach($assignment->checklist->items as $item)
                        @php
                            $submission = $assignment->submissions->where('item_id', $item->id)->first();
                        @endphp

                        <div class="card mb-3 {{ $submission && $submission->is_checked ? 'border-success' : 'border-warning' }}">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-list me-2"></i>
                                    {{ $item->content }}
                                </h6>
                                @if($submission && $submission->is_checked)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Tamamlandı
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Bekliyor
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($submission && $submission->is_checked)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Tamamlanma Zamanı:</strong> {{ $submission->completed_at->format('d.m.Y H:i') }}</p>
                                            @if($submission->notes)
                                                <p><strong>Notlar:</strong> {{ $submission->notes }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($submission->photo_path)
                                                <img src="{{ asset('storage/' . $submission->photo_path) }}"
                                                     class="img-fluid rounded" alt="Görev Fotoğrafı"
                                                     style="max-height: 200px;">
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="notes_{{ $item->id }}" class="form-label">Notlar (Opsiyonel)</label>
                                                <textarea class="form-control" id="notes_{{ $item->id }}"
                                                          name="notes[{{ $item->id }}]" rows="2"
                                                          placeholder="Görev hakkında notlarınız..."></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Fotoğraf Çek</label>
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-primary"
                                                            onclick="openCamera({{ $item->id }})">
                                                        <i class="fas fa-camera me-2"></i>
                                                        Fotoğraf Çek
                                                    </button>
                                                </div>
                                                <input type="hidden" name="photo_data[{{ $item->id }}]"
                                                       id="photo_data_{{ $item->id }}">
                                                <div id="photo_preview_{{ $item->id }}" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                            <i class="fas fa-save me-2"></i>
                            Görevleri Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-camera me-2"></i>
                    Fotoğraf Çek
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <video id="camera" autoplay style="max-width: 100%; height: 400px;"></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                </div>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-primary" id="captureBtn">
                        <i class="fas fa-camera me-2"></i>
                        Fotoğraf Çek
                    </button>
                    <button type="button" class="btn btn-secondary" id="retakeBtn" style="display: none;">
                        <i class="fas fa-redo me-2"></i>
                        Tekrar Çek
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentItemId = null;
let stream = null;

function openCamera(itemId) {
    currentItemId = itemId;
    const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
    modal.show();

    // Arka kamerayı kullan
    const constraints = {
        video: {
            facingMode: { exact: "environment" },
            width: { ideal: 1280 },
            height: { ideal: 720 }
        }
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(mediaStream) {
            stream = mediaStream;
            document.getElementById('camera').srcObject = mediaStream;
        })
        .catch(function(err) {
            console.error('Arka kamera bulunamadı:', err);

            // Arka kamera bulunamazsa ön kamerayı dene
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user",
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            })
            .then(function(mediaStream) {
                stream = mediaStream;
                document.getElementById('camera').srcObject = mediaStream;
            })
            .catch(function(err) {
                alert('Kamera erişimi sağlanamadı: ' + err.message);
            });
        });
}

document.getElementById('captureBtn').addEventListener('click', function() {
    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);

    const photoData = canvas.toDataURL('image/jpeg');
    document.getElementById('photo_data_' + currentItemId).value = photoData;

    // Preview göster
    const preview = document.getElementById('photo_preview_' + currentItemId);
    preview.innerHTML = '<img src="' + photoData + '" class="img-fluid rounded" style="max-height: 150px;">';

    // Modal'ı kapat
    bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();

    // Stream'i durdur
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }

    checkFormCompletion();
});

document.getElementById('retakeBtn').addEventListener('click', function() {
    document.getElementById('photo_preview_' + currentItemId).innerHTML = '';
    document.getElementById('photo_data_' + currentItemId).value = '';
    document.getElementById('captureBtn').style.display = 'inline-block';
    document.getElementById('retakeBtn').style.display = 'none';
});

// Modal kapandığında stream'i durdur
document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});

function checkFormCompletion() {
    const totalItems = {{ $assignment->checklist->items->count() }};
    const completedItems = document.querySelectorAll('input[name^="photo_data"]').length;

    if (completedItems === totalItems) {
        document.getElementById('submitBtn').disabled = false;
    }
}
</script>
@endpush
