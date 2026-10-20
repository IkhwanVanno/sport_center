@extends('layouts.customer')

@section('title', 'Profil & Pengaturan Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Page Header & Breadcrumb -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active text-emerald fw-semibold" aria-current="page">Profil Saya</li>
                </ol>
            </nav>
            <h1 class="h3 fw-extrabold text-dark mb-1">Profil & Keamanan Akun</h1>
            <p class="text-muted small mb-0">Kelola identitas diri, nomor kontak WhatsApp, dan kata sandi akun Anda.</p>
        </div>

        <!-- Profile Edit Card -->
        <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
            <h5 class="fw-bold text-dark border-bottom pb-3 mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-person-lines-fill text-emerald fs-5"></i>
                <span>Informasi Pribadi</span>
            </h5>

            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                @method('PUT')

                <!-- Email (Read-Only) -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control bg-light text-muted" value="{{ $user->email }}" disabled readonly>
                    </div>
                    <div class="form-text small">Email akun tidak dapat diubah setelah pendaftaran.</div>
                </div>

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nomor HP / WhatsApp -->
                <div class="mb-4">
                    <label for="phone_number" class="form-label small fw-semibold text-secondary">Nomor WhatsApp / HP</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-whatsapp"></i></span>
                        <input type="text" 
                               id="phone_number" 
                               name="phone_number" 
                               class="form-control @error('phone_number') is-invalid @enderror" 
                               value="{{ old('phone_number', $user->phone_number) }}" 
                               placeholder="Contoh: 081234567890">
                    </div>
                    @error('phone_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text small">Nomor WhatsApp digunakan untuk konfirmasi jadwal dan notifikasi penting.</div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold text-dark border-bottom pb-3 mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock text-indigo fs-5"></i>
                    <span>Ubah Password (Opsional)</span>
                </h5>

                <!-- Current Password -->
                <div class="mb-3">
                    <label for="current_password" class="form-label small fw-semibold text-secondary">Password Saat Ini</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-key"></i></span>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                    @error('current_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label small fw-semibold text-secondary">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 8 karakter">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-shield-check"></i></span>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-emerald px-4 py-2.5 rounded-3 fw-bold shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
