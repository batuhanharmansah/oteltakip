@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-history me-2"></i>
                    QR Tarama Geçmişi
                </h1>
                <div>
                    <a href="{{ route('admin.qr-codes.history.export') }}?{{ request()->getQueryString() }}"
                       class="btn btn-success me-2">
                        <i class="fas fa-download me-2"></i>
                        Excel İndir
                    </a>
                    <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        QR Kodlara Dön
                    </a>
                </div>
            </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tüm QR Tarama Kayıtları</h5>
            </div>
            <div class="card-body">
                @if($qrScans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Çalışan</th>
                                    <th>Lokasyon</th>
                                    <th>Tarama Tarihi</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($qrScans as $scan)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $scan->user->full_name }}</strong><br>
                                            <small class="text-muted">{{ $scan->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $scan->qrCode->location }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $scan->scanned_at->format('d.m.Y') }}</strong><br>
                                            <small class="text-muted">{{ $scan->scanned_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($scan->scanned_at->isToday())
                                            <span class="badge bg-success">Bugün</span>
                                        @elseif($scan->scanned_at->isYesterday())
                                            <span class="badge bg-primary">Dün</span>
                                        @elseif($scan->scanned_at->isPast())
                                            <span class="badge bg-secondary">Geçmiş</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#scanModal{{ $scan->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.users.history', $scan->user) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal for scan details -->
                                <div class="modal fade" id="scanModal{{ $scan->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    QR Tarama Detayları
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Çalışan Bilgileri</h6>
                                                        <p><strong>Ad Soyad:</strong> {{ $scan->user->full_name }}</p>
                                                        <p><strong>E-posta:</strong> {{ $scan->user->email }}</p>
                                                        <p><strong>Telefon:</strong> {{ $scan->user->phone ?? 'Belirtilmemiş' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>QR Kod Bilgileri</h6>
                                                        <p><strong>Lokasyon:</strong> {{ $scan->qrCode->location }}</p>
                                                                                                                <p><strong>Kullanım:</strong>
                                                            <span class="badge bg-info">Giriş/Çıkış</span>
                                                        </p>
                                                        <p><strong>Kod:</strong> <code>{{ Str::limit($scan->qrCode->code, 20) }}</code></p>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Tarama Bilgileri</h6>
                                                        <p><strong>Tarih:</strong> {{ $scan->scanned_at->format('d.m.Y') }}</p>
                                                        <p><strong>Saat:</strong> {{ $scan->scanned_at->format('H:i:s') }}</p>
                                                        <p><strong>Zaman:</strong> {{ $scan->scanned_at->diffForHumans() }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Durum</h6>
                                                        @if($scan->scanned_at->isToday())
                                                            <p><span class="badge bg-success">Bugün Tarandı</span></p>
                                                        @elseif($scan->scanned_at->isYesterday())
                                                            <p><span class="badge bg-primary">Dün Tarandı</span></p>
                                                        @elseif($scan->scanned_at->isPast())
                                                            <p><span class="badge bg-secondary">Geçmiş Tarama</span></p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                <a href="{{ route('admin.users.history', $scan->user) }}" class="btn btn-primary">
                                                    <i class="fas fa-user me-1"></i>
                                                    Kullanıcı Geçmişi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $qrScans->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Henüz QR tarama kaydı bulunmuyor.</p>
                        <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            İlk QR Kodu Oluştur
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Card -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $qrScans->count() }}</h4>
                <small>Toplam Tarama</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $qrScans->unique('user_id')->count() }}</h4>
                <small>Farklı Çalışan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $qrScans->unique('qr_code_id')->count() }}</h4>
                <small>Farklı QR Kod</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $qrScans->where('scanned_at', '>=', now()->startOfDay())->count() }}</h4>
                <small>Bugünkü Tarama</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Filtreleme</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="user_filter" class="form-label">Çalışan</label>
                        <select name="user_id" id="user_filter" class="form-select">
                            <option value="">Tümü</option>
                            @foreach($allUsers as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="location_filter" class="form-label">Lokasyon</label>
                        <select name="location" id="location_filter" class="form-select">
                            <option value="">Tümü</option>
                            @foreach($allLocations as $location)
                                <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                    {{ $location }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_filter" class="form-label">Tarih</label>
                        <input type="date" name="date" id="date_filter" class="form-control"
                               value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Filtrele
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
