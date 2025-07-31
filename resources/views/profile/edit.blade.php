@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user me-2"></i>
                Profil Ayarları
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Profile Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit me-2"></i>
                    Profil Bilgileri
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Ad *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="surname" class="form-label">Soyad *</label>
                            <input type="text" class="form-control @error('surname') is-invalid @enderror"
                                   id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                            @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefon</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Adres</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="emergency_contact_name" class="form-label">Acil Durum Kişi Adı</label>
                            <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                   id="emergency_contact_name" name="emergency_contact_name"
                                   value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}">
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="emergency_contact_phone" class="form-label">Acil Durum Telefonu</label>
                            <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                   id="emergency_contact_phone" name="emergency_contact_phone"
                                   value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}">
                            @error('emergency_contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Profili Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lock me-2"></i>
                    Şifre Değiştir
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mevcut Şifre *</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Yeni Şifre *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Yeni Şifre Tekrar *</label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>
                            Şifreyi Değiştir
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Hesabı Sil
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Dikkat!</h6>
                    <p class="mb-0">Hesabınızı sildiğinizde, tüm verileriniz kalıcı olarak silinecektir. Bu işlem geri alınamaz.</p>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('delete')

                    <div class="mb-3">
                        <label for="password_delete" class="form-label">Şifrenizi onaylamak için girin *</label>
                        <input type="password" class="form-control @error('password_delete') is-invalid @enderror"
                               id="password_delete" name="password_delete" required>
                        @error('password_delete')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Hesabınızı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')">
                            <i class="fas fa-trash me-2"></i>
                            Hesabı Sil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- User Info Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Hesap Bilgileri
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <h5>{{ $user->full_name }}</h5>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'success' }} mt-2">
                        {{ $user->role == 'admin' ? 'Yönetici' : 'Çalışan' }}
                    </span>
                </div>

                <hr>

                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-muted">Üye Olma</h6>
                        <p class="mb-0">{{ $user->created_at->format('d.m.Y') }}</p>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted">Son Güncelleme</h6>
                        <p class="mb-0">{{ $user->updated_at->format('d.m.Y') }}</p>
                    </div>
                </div>

                @if($user->phone)
                <hr>
                <div>
                    <h6 class="text-muted">İletişim</h6>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $user->phone }}</p>
                    @if($user->emergency_contact_name)
                        <p class="mb-1"><i class="fas fa-user-shield me-2"></i>{{ $user->emergency_contact_name }}</p>
                    @endif
                    @if($user->emergency_contact_phone)
                        <p class="mb-0"><i class="fas fa-phone-alt me-2"></i>{{ $user->emergency_contact_phone }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
