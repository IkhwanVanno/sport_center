@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card p-3 p-md-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-1">Buat Akun Baru</h4>
                        <p class="text-muted small">Lengkapi data untuk melakukan reservasi lapangan</p>
                    </div>

                    {{-- Dynamic Alert Component --}}
                    @include('components.alert')

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="name" class="form-label text-secondary small fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       placeholder="John Doe" 
                                       required 
                                       autofocus>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-secondary small fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       placeholder="nama@email.com" 
                                       required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor HP/WhatsApp -->
                        <div class="mb-3">
                            <label for="phone_number" class="form-label text-secondary small fw-semibold">Nomor HP / WhatsApp <span class="text-muted fs-7">(Opsional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                <input type="text" 
                                       id="phone_number" 
                                       name="phone_number" 
                                       class="form-control @error('phone_number') is-invalid @enderror" 
                                       value="{{ old('phone_number') }}" 
                                       placeholder="081234567890">
                            </div>
                            @error('phone_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-secondary small fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Minimal 8 karakter" 
                                       required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label text-secondary small fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-shield-check"></i></span>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-control" 
                                       placeholder="Ulangi password" 
                                       required>
                            </div>
                        </div>

                        <!-- Syarat & Ketentuan -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input @error('terms') is-invalid @enderror" 
                                   id="terms" 
                                   name="terms" 
                                   value="1" 
                                   {{ old('terms') ? 'checked' : '' }} 
                                   required>
                            <label class="form-check-label small text-muted" for="terms">
                                Saya menyetujui <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> yang berlaku.
                            </label>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary fw-bold py-2">
                                Daftar Akun
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <span class="small text-muted">Sudah memiliki akun?</span>
                            <a href="{{ route('login') }}" class="small fw-semibold text-decoration-none">Masuk</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection