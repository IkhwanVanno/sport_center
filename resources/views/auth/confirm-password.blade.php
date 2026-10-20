@extends('layouts.app')

@section('title', 'Konfirmasi Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card auth-card p-3 p-md-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mb-2">
                            <span class="fs-1">🛡️</span>
                        </div>
                        <h4 class="fw-bold mb-1">Area Dilindungi</h4>
                        <p class="text-muted small">Silakan konfirmasi password Anda untuk melanjutkan tindakan ini</p>
                    </div>

                    @include('components.alert')

                    <form method="POST" action="{{ Route::has('password.confirm') ? route('password.confirm') : url('/confirm-password') }}">
                        @csrf

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-secondary small fw-semibold">Password Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="••••••••" 
                                       required 
                                       autofocus>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-emerald fw-bold py-2">
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
