@extends('layouts.app')

@section('title', 'Login - Sistem Booking Lapangan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card auth-card p-3 p-md-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-1">Selamat Datang</h4>
                        <p class="text-muted small">Silakan masuk ke akun Anda</p>
                    </div>

                    {{-- Dynamic Alert Component --}}
                    @include('components.alert')

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

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
                                       required 
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label text-secondary small fw-semibold">Password</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="••••••••" 
                                       required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label small text-muted" for="remember_me">Ingat saya di perangkat ini</label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary fw-bold py-2">
                                Masuk
                            </button>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <span class="small text-muted">Belum punya akun?</span>
                            <a href="{{ route('register') }}" class="small fw-semibold text-decoration-none">Daftar Sekarang</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection