@extends('layouts.modern')

@section('page-title', 'QR Kod Yönetimi')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">QR Kod Yönetimi</h2>
                <p class="text-muted mb-0">Sistem QR kodlarını yönetin</p>
            </div>
            <div>
                <a href="{{ route('admin.qr-codes.history') }}" class="btn btn-info me-2">
                    <i class="fas fa-history me-2"></i>
                    QR Geçmişi
                </a>
                <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Yeni QR Kod
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tüm QR Kodlar</h5>
            </div>
            <div class="card-body">
                @if($qrCodes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Lokasyon</th>
                                    <th>Kod</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($qrCodes as $qrCode)
                                <tr>
                                    <td>{{ $qrCode->location }}</td>
                                    <td>
                                        <code>{{ Str::limit($qrCode->code, 20) }}</code>
                                    </td>
                                    <td>{{ $qrCode->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.qr-codes.download', $qrCode) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('admin.qr-codes.destroy', $qrCode) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu QR kodu silmek istediğinizden emin misiniz?')">
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
                        {{ $qrCodes->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Henüz QR kod bulunmuyor.</p>
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
@endsection
