@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">
            <i class="fas fa-qrcode me-2"></i>
            QR Kod Okuyucu
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-camera me-2"></i>
                    Kamera
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="cameraSelect" class="form-label">Kamera Seçimi:</label>
                    <select id="cameraSelect" class="form-select mb-2">
                        <option value="environment">Arka Kamera (Önerilen)</option>
                        <option value="user">Ön Kamera</option>
                    </select>
                </div>

                <div id="cameraContainer" class="text-center">
                    <video id="video" autoplay playsinline style="width:100%;max-width:480px;border:2px solid #ccc;border-radius:10px;"></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                </div>

                <div class="mt-3">
                    <button id="startButton" class="btn btn-primary">
                        <i class="fas fa-play me-2"></i>
                        Kamerayı Başlat
                    </button>
                    <button id="stopButton" class="btn btn-danger" style="display: none;">
                        <i class="fas fa-stop me-2"></i>
                        Kamerayı Durdur
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Sonuç
                </h5>
            </div>
            <div class="card-body">
                <div id="result" class="alert alert-info" style="display: none;">
                    <div id="resultMessage"></div>
                    <div id="resultDetails" class="mt-2"></div>
                </div>

                <div class="mt-3">
                    <h6>Kullanım Talimatları:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Arka kamerayı seçin (önerilen)</li>
                        <li><i class="fas fa-check text-success me-2"></i>Kamerayı başlatın</li>
                        <li><i class="fas fa-check text-success me-2"></i>QR kodu kameraya gösterin</li>
                        <li><i class="fas fa-check text-success me-2"></i>Otomatik olarak kaydedilecektir</li>
                    </ul>

                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Mobil Cihazlar İçin:</strong><br>
                            • Kamera izni vermeniz gerekiyor<br>
                            • Arka kamera daha iyi sonuç verir<br>
                            • QR kodu sabit tutun
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
#cameraContainer {
    width: 100%;
    height: 400px;
    border: 2px solid #ddd;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

#video {
    max-width: 100%;
    max-height: 100%;
    border-radius: 8px;
}

/* Mobil cihazlar için optimize edilmiş stiller */
@media (max-width: 768px) {
    #cameraContainer {
        height: 300px;
    }

    .card-body {
        padding: 1rem;
    }

    .btn {
        font-size: 14px;
        padding: 8px 16px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
let stream = null;
let isScanning = false;
let scanInterval = null;

document.getElementById('startButton').addEventListener('click', startCamera);
document.getElementById('stopButton').addEventListener('click', stopCamera);

function startCamera() {
    if (isScanning) return;

    const video = document.getElementById('video');
    const selectedCamera = document.getElementById('cameraSelect').value;

    // Kamera ayarları
    const constraints = {
        video: {
            facingMode: { exact: selectedCamera },
            width: { ideal: 1280 },
            height: { ideal: 720 }
        }
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(mediaStream) {
            stream = mediaStream;
            video.srcObject = stream;
            isScanning = true;

            document.getElementById('startButton').style.display = 'none';
            document.getElementById('stopButton').style.display = 'inline-block';

            // QR kod taramayı başlat
            startQRScanning();
        })
        .catch(function(err) {
            console.error('Kamera başlatılamadı:', err);

            // Mobil cihazlar için daha detaylı hata mesajları
            let errorMessage = 'Kamera başlatılamadı. ';

            if (err.name === 'NotAllowedError') {
                errorMessage += 'Kamera izni reddedildi. Lütfen tarayıcı ayarlarından kamera iznini verin.';
            } else if (err.name === 'NotFoundError') {
                errorMessage += 'Kamera bulunamadı. Lütfen cihazınızda kamera olduğundan emin olun.';
            } else if (err.name === 'NotSupportedError') {
                errorMessage += 'Bu cihaz kamera erişimini desteklemiyor.';
            } else if (err.name === 'OverconstrainedError') {
                errorMessage += 'Seçilen kamera bulunamadı. Lütfen farklı bir kamera seçin.';
            } else {
                errorMessage += 'Bilinmeyen bir hata oluştu. Lütfen sayfayı yenileyin.';
            }

            alert(errorMessage);
        });
}

function stopCamera() {
    if (!isScanning) return;

    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }

    if (scanInterval) {
        clearInterval(scanInterval);
        scanInterval = null;
    }

    isScanning = false;
    document.getElementById('startButton').style.display = 'inline-block';
    document.getElementById('stopButton').style.display = 'none';

    const video = document.getElementById('video');
    video.srcObject = null;
}

function startQRScanning() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');

    scanInterval = setInterval(() => {
        if (video.videoWidth === 0) return;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            console.log('QR kod bulundu:', code.data);
            processQRCode(code.data);
            stopCamera();
        }
    }, 100); // Her 100ms'de bir kontrol et
}

function processQRCode(qrData) {
    // QR kod okundu, sunucuya gönder
    fetch('{{ route("qr-scan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            code: qrData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showResult('success', data.message, data.data);
        } else {
            showResult('error', data.message);
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        showResult('error', 'Bir hata oluştu. Lütfen tekrar deneyin.');
    });
}

function showResult(type, message, details = null) {
    const resultDiv = document.getElementById('result');
    const messageDiv = document.getElementById('resultMessage');
    const detailsDiv = document.getElementById('resultDetails');

    resultDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
    messageDiv.textContent = message;

    if (details) {
        detailsDiv.innerHTML = `
            <small class="text-muted">
                <strong>Lokasyon:</strong> ${details.location}<br>
                <strong>Saat:</strong> ${details.scanned_at}
            </small>
        `;
    } else {
        detailsDiv.innerHTML = '';
    }

    resultDiv.style.display = 'block';

    // 5 saniye sonra sonucu gizle
    setTimeout(() => {
        resultDiv.style.display = 'none';
    }, 5000);
}

// Kamera seçimi değiştiğinde yeniden başlat
document.getElementById('cameraSelect').addEventListener('change', () => {
    if (isScanning) {
        stopCamera();
        setTimeout(() => {
            startCamera();
        }, 500);
    }
});

// Sayfa yüklendiğinde otomatik başlat
window.addEventListener('load', () => {
    // Mobil cihazlar için daha uzun bekleme süresi
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const delay = isMobile ? 2000 : 1000;

    setTimeout(() => {
        startCamera();
    }, delay);
});
</script>
@endpush
