@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card auth-card p-3 p-md-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mb-2">
                            <span class="fs-1">🔑</span>
                        </div>
                        <h4 class="fw-bold mb-1">Lupa Password?</h4>
                        <p class="text-muted small">Masukkan email yang terdaftar untuk menerima instruksi reset password</p>
                    </div>

                    @include('components.alert')

                    <form method="POST" action="{{ Route::has('password.email') ? route('password.email') : url('/forgot-password') }}">
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

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-emerald fw-bold py-2">
                                Kirim Link Reset
                            </button>
                        </div>

                        <!-- Back to login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="small fw-semibold text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Halaman Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
