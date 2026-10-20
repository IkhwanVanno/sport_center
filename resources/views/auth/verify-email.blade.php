@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card p-3 p-md-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="fs-1">✉️</span>
                    </div>
                    <h4 class="fw-bold mb-2">Verifikasi Alamat Email</h4>
                    <p class="text-muted small mb-4">
                        Terima kasih telah mendaftar! Sebelum mulai memesan lapangan, silakan periksa kotak masuk email Anda dan klik tautan verifikasi yang baru saja kami kirimkan.
                    </p>

                    @include('components.alert')

                    <div class="d-flex flex-column gap-3">
                        <form method="POST" action="{{ Route::has('verification.send') ? route('verification.send') : url('/email/verification-notification') }}">
                            @csrf
                            <button type="submit" class="btn btn-emerald w-100 fw-bold py-2">
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted small text-decoration-none">
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
