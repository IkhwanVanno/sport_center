@extends('layouts.customer')

@section('title', 'Unggah Bukti Transfer - ' . $booking->booking_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('user.payments.checkout', $booking->booking_code) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Instruksi Pembayaran
            </a>
        </div>

        <!-- Upload Card -->
        <div class="card card-custom p-4 border-0 shadow-sm">
            <div class="text-center mb-4">
                <div class="mb-2">
                    <span class="fs-1 text-emerald">📤</span>
                </div>
                <h4 class="fw-bold mb-1 text-dark">Unggah Bukti Transfer</h4>
                <p class="text-muted small">Kirim foto struk ATM / tangkapan layar m-Banking untuk verifikasi pembayaran.</p>
            </div>

            <!-- Booking Summary Box -->
            <div class="bg-light p-3 rounded-4 mb-4">
                <div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted">Kode Booking:</span>
                    <strong class="font-monospace text-dark">{{ $booking->booking_code }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted">Lapangan:</span>
                    <span class="fw-semibold text-dark">{{ $booking->court->name ?? 'Lapangan' }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Nominal Harus Ditransfer:</span>
                    <strong class="text-emerald fs-6">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
                </div>
            </div>

            <!-- Upload Form -->
            <form method="POST" action="{{ route('user.payments.upload-proof', $booking->booking_code) }}" enctype="multipart/form-data">
                @csrf

                <!-- Bank Pengirim -->
                <div class="mb-3">
                    <label for="bank_sender" class="form-label small fw-semibold text-secondary">Bank / E-Wallet Pengirim <span class="text-muted fs-7">(Opsional)</span></label>
                    <input type="text" 
                           id="bank_sender" 
                           name="bank_sender" 
                           value="{{ old('bank_sender') }}" 
                           class="form-control" 
                           placeholder="Contoh: BCA a.n. John Doe">
                </div>

                <!-- File Bukti Transfer -->
                <div class="mb-3">
                    <label for="proof_of_payment" class="form-label small fw-semibold text-secondary">File Foto Bukti Pembayaran <span class="text-danger">*</span></label>
                    <input type="file" 
                           id="proof_of_payment" 
                           name="proof_of_payment" 
                           class="form-control @error('proof_of_payment') is-invalid @enderror" 
                           accept="image/jpeg,image/png,image/jpg,image/webp" 
                           required>
                    @error('proof_of_payment')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text small">Format JPG, PNG, atau WEBP. Maksimal ukuran file 3 MB.</div>
                </div>

                <!-- Image Preview Area -->
                <div id="preview-wrapper" class="text-center mb-4 d-none">
                    <div class="text-muted small mb-2 fw-semibold">Pratinjau Foto Bukti:</div>
                    <img id="image-preview" src="#" alt="Pratinjau" class="img-thumbnail rounded-3 shadow-sm" style="max-height: 220px;">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-emerald py-2.5 rounded-3 fw-bold shadow-sm">
                        Kirim Bukti Pembayaran
                    </button>
                    <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="btn btn-link text-muted small text-decoration-none">
                        Batal & Kembali
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('proof_of_payment');
    const previewWrapper = document.getElementById('preview-wrapper');
    const imagePreview = document.getElementById('image-preview');

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                previewWrapper.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            previewWrapper.classList.add('d-none');
        }
    });
});
</script>
@endpush
