@extends('layouts.modern')

@section('page-title', 'Bugünkü Görevler')

@section('content')
<style>
.camera-modal .modal-dialog {
    max-width: 100vw;
    width: 100vw;
    height: 100vh;
    margin: 0;
}
.camera-modal .modal-content {
    height: 100vh;
    border-radius: 0;
    border: none;
}
.camera-modal .modal-header {
    background: rgba(0,0,0,0.8);
    color: white;
    border: none;
    padding: 1rem;
}
.camera-modal .modal-body {
    padding: 0;
    background: #000;
    display: flex;
    flex-direction: column;
    height: calc(100vh - 120px);
}
.camera-container {
    flex: 1;
    width: 100%;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.camera-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background: #000;
}
.camera-buttons {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 1rem;
    z-index: 1000;
}
.camera-buttons .btn {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    border-radius: 50px;
    min-width: 120px;
}
.capture-btn {
    background: #28a745;
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
}
.capture-btn:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6);
}
</style>
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Aktif Görevler</h2>
                <p class="text-muted mb-0">Bugünkü görevlerinizi tamamlayın</p>
            </div>
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
                <form action="{{ route('employee.submissions.store') }}" method="POST" enctype="multipart/form-data" id="submissionForm">
                    @csrf
                    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                    <div class="alert alert-info">
                        <h6><i class="fas fa-camera me-2"></i>Fotoğraf Çekme Kuralları</h6>
                        <ul class="mb-0">
                            <li>Her görev maddesi için fotoğraf çekmeniz gerekiyor</li>
                            <li>Fotoğraf çekmeden görev tamamlanamaz</li>
                            <li>Kamera açıldığında "Çek" butonuna basın</li>
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
                                            @if($submission->photo_path || $submission->photo_data)
                                                @if($submission->photo_path && file_exists(public_path($submission->photo_path)))
                                                    <img src="{{ asset($submission->photo_path) }}"
                                                         class="img-fluid rounded" alt="Görev Fotoğrafı"
                                                         style="max-height: 200px;">
                                                @elseif($submission->photo_data)
                                                    <img src="data:image/jpeg;base64,{{ substr($submission->photo_data, strpos($submission->photo_data, ',') + 1) }}"
                                                         class="img-fluid rounded" alt="Görev Fotoğrafı"
                                                         style="max-height: 200px;">
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        Fotoğraf bulunamadı
                                                    </div>
                                                @endif
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
                                                            onclick="startCamera({{ $item->id }})">
                                                        <i class="fas fa-camera me-2"></i>
                                                        Kamera Başlat
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

<!-- Full Screen Camera Modal -->
<div class="modal fade camera-modal" id="cameraModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-0">
                    <i class="fas fa-camera me-2"></i>
                    Fotoğraf Çek
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="camera-container">
                    <video id="camera" autoplay class="camera-video"></video>
                </div>
                <canvas id="canvas" style="display: none;"></canvas>
                <div class="camera-buttons">
                    <button type="button" class="btn capture-btn" id="captureBtn">
                        <i class="fas fa-camera me-2"></i>
                        Fotoğraf Çek
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

function startCamera(itemId) {
    currentItemId = itemId;
    const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
    modal.show();

    // Tam ekran için yüksek çözünürlük kullan
    const constraints = {
        video: {
            facingMode: { exact: "environment" },
            width: { ideal: 1920, max: 3840 },
            height: { ideal: 1080, max: 2160 }
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
                    width: { ideal: 1920, max: 3840 },
                    height: { ideal: 1080, max: 2160 }
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

    // Tam ekran boyutlarda çek
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);

    const photoData = canvas.toDataURL('image/jpeg', 0.9); // Yüksek kalite
    document.getElementById('photo_data_' + currentItemId).value = photoData;

    // Preview göster
    const preview = document.getElementById('photo_preview_' + currentItemId);
    preview.innerHTML = '<img src="' + photoData + '" class="img-fluid rounded" style="max-height: 200px;">';

    // Stream'i durdur
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }

    // Modal'ı kapat
    bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();

    // Buton metnini değiştir
    const button = document.querySelector(`button[onclick="startCamera(${currentItemId})"]`);
    button.innerHTML = '<i class="fas fa-check me-2"></i>Fotoğraf Çekildi';
    button.className = 'btn btn-success';
    button.disabled = true;

    checkFormCompletion();
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

// Form submit debug
document.getElementById('submissionForm').addEventListener('submit', function(e) {
    const photoInputs = document.querySelectorAll('input[name^="photo_data"]');
    console.log('Form submitting with', photoInputs.length, 'photos');

    photoInputs.forEach((input, index) => {
        console.log('Photo', index + 1, 'length:', input.value.length);
    });
});
</script>
@endpush
