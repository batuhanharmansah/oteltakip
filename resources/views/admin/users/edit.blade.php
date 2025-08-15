@extends('layouts.modern')

@section('page-title', 'Kullanıcı Düzenle')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit me-2"></i>
                    Kullanıcı Düzenle: {{ $user->full_name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i> Ad
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="surname" class="form-label">
                                    <i class="fas fa-user me-1"></i> Soyad
                                </label>
                                <input type="text" class="form-control @error('surname') is-invalid @enderror"
                                       id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                                @error('surname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i> E-posta
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i> Telefon
                                </label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="emergency_contact_name" class="form-label">
                                    <i class="fas fa-user-shield me-1"></i> Acil Durum Kişisi
                                </label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                       id="emergency_contact_name" name="emergency_contact_name"
                                       value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="emergency_contact_phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i> Acil Durum Telefonu
                                </label>
                                <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                       id="emergency_contact_phone" name="emergency_contact_phone"
                                       value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i> Adres
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i> Rol
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror"
                                        id="role" name="role" required>
                                    <option value="">Rol Seçin</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        <i class="fas fa-user-shield"></i> Admin
                                    </option>
                                    <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>
                                        <i class="fas fa-user"></i> Çalışan
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> Yeni Şifre (Opsiyonel)
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Değiştirmek istemiyorsanız boş bırakın">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Vardiya Bilgileri (Sadece Çalışanlar için) -->
                    <div id="shift-info" style="display: none;">
                        <hr>
                        <h6 class="mb-3">
                            <i class="fas fa-clock me-2"></i>
                            Vardiya Bilgileri
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shift_type" class="form-label">Vardiya Türü</label>
                                <select class="form-select @error('shift_type') is-invalid @enderror" id="shift_type" name="shift_type">
                                    <option value="day" {{ old('shift_type', $user->shift_type) == 'day' ? 'selected' : '' }}>Gündüz Vardiyası</option>
                                    <option value="night" {{ old('shift_type', $user->shift_type) == 'night' ? 'selected' : '' }}>Gece Vardiyası</option>
                                </select>
                                @error('shift_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label">Başlangıç Saati</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                       id="start_time" name="start_time" value="{{ old('start_time', $user->start_time ? date('H:i', strtotime($user->start_time)) : '08:00') }}">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_time" class="form-label">Bitiş Saati</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                       id="end_time" name="end_time" value="{{ old('end_time', $user->end_time ? date('H:i', strtotime($user->end_time)) : '17:00') }}">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const shiftInfo = document.getElementById('shift-info');
    
    function toggleShiftInfo() {
        if (roleSelect.value === 'employee') {
            shiftInfo.style.display = 'block';
        } else {
            shiftInfo.style.display = 'none';
        }
    }
    
    roleSelect.addEventListener('change', toggleShiftInfo);
    toggleShiftInfo(); // Initial check
});
</script>
@endpush
