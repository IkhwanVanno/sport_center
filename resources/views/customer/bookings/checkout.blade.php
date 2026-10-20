@extends('layouts.customer')

@section('title', 'Pembayaran Booking ' . $booking->booking_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Breadcrumb & Back -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Detail Booking
            </a>

            <span class="badge bg-light text-secondary border font-monospace px-3 py-1.5 fs-7">
                {{ $booking->booking_code }}
            </span>
        </div>

        <!-- 15-Minute Expiry Countdown Alert -->
        <div class="card card-custom border-0 shadow-sm mb-4 bg-slate-900 text-white p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="fs-1 text-warning">⏳</span>
                    <div>
                        <div class="text-secondary small text-uppercase fw-bold">Selesaikan Pembayaran Sebelum:</div>
                        <h4 class="fw-bold mb-0 text-white">
                            {{ $booking->expired_at->format('H:i') }} WIB 
                            <span class="fs-7 fw-normal text-secondary">({{ $booking->expired_at->format('d M Y') }})</span>
                        </h4>
                    </div>
                </div>

                <div class="text-sm-end bg-slate-800 p-3 rounded-4 border border-secondary border-opacity-25">
                    <div class="text-secondary small fw-semibold" style="font-size: 0.72rem;">SISA WAKTU:</div>
                    <div class="fs-4 fw-extrabold text-warning font-monospace" id="countdown-timer">15:00</div>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top border-secondary border-opacity-25 text-secondary small" style="font-size: 0.75rem;">
                <i class="bi bi-shield-exclamation text-warning me-1"></i>
                Slot Anda terkunci selama 15 menit. Jika waktu habis, sistem backend otomatis membatalkan pemesanan dan mengembalikan slot ke publik.
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Order Summary Column -->
            <div class="col-md-5">
                <div class="card card-custom p-4 border-0 shadow-sm h-100">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Ringkasan Tagihan</h6>
                    
                    <div class="mb-2">
                        <span class="text-muted small d-block">Lapangan</span>
                        <strong class="text-dark">{{ $booking->court->name ?? 'Lapangan' }}</strong>
                    </div>

                    <div class="mb-2">
                        <span class="text-muted small d-block">Cabang</span>
                        <span class="text-dark small">{{ $booking->court->branch->name ?? 'Sport Center' }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block">Jadwal Sewa</span>
                        <span class="text-emerald fw-semibold small">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}
                        </span>
                        <div class="text-muted small">
                            {{ substr($booking->items->min('start_time'), 0, 5) }} - {{ substr($booking->items->max('end_time'), 0, 5) }} ({{ $booking->items->count() }} Slot)
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <span class="text-muted small">Total Tagihan:</span>
                        <span class="fs-4 fw-extrabold text-emerald fw-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="bg-light p-2.5 rounded-3 text-center small text-muted" style="font-size: 0.72rem;">
                        Harga sudah termasuk pajak & fasilitas lapangan.
                    </div>
                </div>
            </div>

            <!-- Payment Methods Column -->
            <div class="col-md-7">
                <div class="card card-custom p-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Pilih Metode Pembayaran</h6>

                    <!-- Tab Payment Methods -->
                    <ul class="nav nav-pills mb-3 gap-2" id="paymentTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active btn-sm rounded-pill px-3" id="qris-tab" data-bs-toggle="pill" data-bs-target="#pills-qris" type="button" role="tab">
                                <i class="bi bi-qr-code me-1"></i>QRIS Instan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-sm rounded-pill px-3" id="transfer-tab" data-bs-toggle="pill" data-bs-target="#pills-transfer" type="button" role="tab">
                                <i class="bi bi-bank me-1"></i>Transfer Bank
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="paymentTabContent">
                        <!-- QRIS Tab -->
                        <div class="tab-pane fade show active text-center p-3 border rounded-4 bg-light" id="pills-qris" role="tabpanel">
                            <span class="badge bg-emerald text-white px-3 py-1 rounded-pill small mb-2">Verifikasi Otomatis</span>
                            <div class="p-2 bg-white rounded-3 shadow-sm d-inline-block mb-3">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode('QRIS:SPORTCENTER:' . $booking->booking_code . ':' . (int)$booking->total_amount) }}" 
                                     alt="QRIS QR Code" 
                                     class="img-fluid" 
                                     style="width: 170px; height: 170px;">
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">Scan dengan Aplikasi Bank / E-Wallet</h6>
                            <p class="text-muted small mb-3">BCA, Mandiri, BRI, BNI, GoPay, OVO, DANA, ShopeePay</p>

                            <div class="d-flex justify-content-center">
                                <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="btn btn-emerald btn-sm px-4 py-2 rounded-pill fw-semibold">
                                    <i class="bi bi-arrow-repeat me-1"></i>Cek Status Pembayaran
                                </a>
                            </div>
                        </div>

                        <!-- Transfer Bank Tab -->
                        <div class="tab-pane fade p-3 border rounded-4 bg-light" id="pills-transfer" role="tabpanel">
                            <div class="alert alert-info py-2 px-3 small mb-3">
                                <i class="bi bi-info-circle-fill me-1"></i>Setelah transfer, Anda wajib mengunggah foto bukti transfer.
                            </div>

                            <div class="bg-white p-3 rounded-3 border mb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary text-white mb-1">BCA</span>
                                    <div class="fw-bold font-monospace fs-6 text-dark">123-456-7890</div>
                                    <div class="text-muted small">a.n. PT Sport Center Indonesia</div>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor rekening BCA disalin!');">
                                    Salin
                                </button>
                            </div>

                            <div class="bg-white p-3 rounded-3 border mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-warning text-dark mb-1">MANDIRI</span>
                                    <div class="fw-bold font-monospace fs-6 text-dark">987-654-3210</div>
                                    <div class="text-muted small">a.n. PT Sport Center Indonesia</div>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="navigator.clipboard.writeText('9876543210'); alert('Nomor rekening Mandiri disalin!');">
                                    Salin
                                </button>
                            </div>

                            <div class="d-grid">
                                <a href="{{ route('user.payments.upload-proof-form', $booking->booking_code) }}" class="btn btn-emerald py-2.5 rounded-3 fw-bold">
                                    <i class="bi bi-cloud-arrow-up-fill me-1"></i>Unggah Bukti Transfer Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const expiryTime = new Date("{{ $booking->expired_at->toIso8601String() }}").getTime();
    const timerDisplay = document.getElementById('countdown-timer');

    function updateTimer() {
        const now = new Date().getTime();
        const distance = expiryTime - now;

        if (distance <= 0) {
            timerDisplay.innerText = "00:00 (Kedaluwarsa)";
            timerDisplay.classList.remove('text-warning');
            timerDisplay.classList.add('text-danger');
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const mStr = minutes < 10 ? '0' + minutes : minutes;
        const sStr = seconds < 10 ? '0' + seconds : seconds;

        timerDisplay.innerText = mStr + ":" + sStr;
    }

    updateTimer();
    setInterval(updateTimer, 1000);
});
</script>
@endpush
