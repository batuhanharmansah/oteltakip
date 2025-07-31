@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-qrcode me-2"></i>
                Yeni QR Kod Oluştur
            </h1>
            <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-secondary">
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
                <h5 class="card-title mb-0">QR Kod Bilgileri</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.qr-codes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasyon *</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                               id="location" name="location" value="{{ old('location') }}"
                               placeholder="Örn: Giriş Kapısı, Mutfak, Oda 101" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>QR Kod Kullanımı</h6>
                        <p class="mb-0">Bu QR kod hem giriş hem çıkış için kullanılacak. Personel işe geldiğinde ve çıkarken aynı QR kodu okutacak.</p>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-secondary me-2">İptal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            QR Kod Oluştur
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
                    QR Kod Tipleri
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><span class="badge bg-info">Giriş/Çıkış</span></h6>
                    <p class="mb-0"><small>Aynı QR kod hem giriş hem çıkış için kullanılır.</small></p>
                </div>

                <div class="mb-3">
                    <h6><span class="badge bg-primary">Lokasyon Bazlı</span></h6>
                    <p class="mb-0"><small>Her lokasyon için ayrı QR kod oluşturulur.</small></p>
                </div>

                <div class="mb-3">
                    <h6><span class="badge bg-success">Otomatik Kayıt</span></h6>
                    <p class="mb-0"><small>QR kod okutulduğunda otomatik olarak kaydedilir.</small></p>
                </div>

                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-lightbulb me-1"></i>
                        QR kod oluşturulduktan sonra PNG formatında indirilebilir.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
