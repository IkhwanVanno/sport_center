@extends('layouts.customer')

@section('title', 'E-Ticket & Detail Booking ' . $booking->booking_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Navigation & Actions Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="{{ route('user.bookings.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-semibold d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Riwayat</span>
            </a>

            <div class="d-flex gap-2">
                <button type="button" onclick="window.print()" class="btn btn-outline-dark btn-sm px-3 rounded-pill fw-semibold d-flex align-items-center gap-1">
                    <i class="bi bi-printer"></i>
                    <span>Cetak E-Ticket</span>
                </button>

                @if($booking->payment_status === 'unpaid' && $booking->status === 'pending' && $booking->expired_at->isFuture())
                    <a href="{{ route('user.payments.checkout', $booking->booking_code) }}" class="btn btn-emerald btn-sm px-4 rounded-pill fw-bold">
                        Bayar Sekarang &rarr;
                    </a>
                @endif
            </div>
        </div>

        <!-- E-Ticket Card -->
        <div class="card card-custom border-0 shadow-lg overflow-hidden position-relative mb-4">
            <!-- Header Ticket Banner -->
            <div class="bg-slate-900 p-4 text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4">⚽</span>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">E-TICKET SPORT CENTER</h5>
                        <div class="text-secondary small">Bukti Resmi Reservasi Lapangan</div>
                    </div>
                </div>

                <div class="text-end">
                    <span class="badge bg-light text-dark font-monospace fs-6 px-3 py-1.5 shadow-sm">
                        {{ $booking->booking_code }}
                    </span>
                </div>
            </div>

            <!-- Body Details -->
            <div class="p-4 p-md-5">
                <!-- Status Row -->
                <div class="row g-3 p-3 bg-light rounded-4 mb-4 align-items-center">
                    <div class="col-sm-6">
                        <span class="text-muted small d-block">Status Reservasi:</span>
                        <div class="mt-1">
                            <x-badge-status :status="$booking->status" type="booking" />
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <span class="text-muted small d-block">Status Pembayaran:</span>
                        <div class="mt-1">
                            <x-badge-status :status="$booking->payment_status" type="payment" />
                        </div>
                    </div>
                </div>

                <!-- QR Code & Key Instructions -->
                <div class="row g-4 align-items-center mb-4">
                    <div class="col-md-4 text-center">
                        <div class="p-3 bg-white border rounded-4 shadow-sm d-inline-block">
                            <!-- QR Code Generator API -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($booking->booking_code) }}" 
                                 alt="QR Code {{ $booking->booking_code }}" 
                                 class="img-fluid rounded" 
                                 style="width: 150px; height: 150px;">
                            <div class="font-monospace small fw-bold text-dark mt-2">{{ $booking->booking_code }}</div>
                        </div>
                        <div class="text-muted small mt-2" style="font-size: 0.72rem;">Tunjukkan QR Code ini ke petugas kasir saat tiba di lokasi.</div>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="text-muted small fw-semibold d-block">Nama Pelanggan</label>
                                <span class="fw-bold text-dark fs-6">{{ $booking->user->name ?? 'Pelanggan' }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small fw-semibold d-block">Kontak / WhatsApp</label>
                                <span class="fw-bold text-dark fs-6">{{ $booking->user->phone_number ?? '-' }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small fw-semibold d-block">Lapangan</label>
                                <span class="fw-bold text-dark">{{ $booking->court->name ?? 'Lapangan' }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small fw-semibold d-block">Cabang</label>
                                <span class="fw-bold text-dark">{{ $booking->court->branch->name ?? 'Sport Center' }}</span>
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ $booking->court->branch->address ?? '' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small fw-semibold d-block">Tanggal Bermain</label>
                                <span class="fw-bold text-emerald fs-6">
                                    <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                                </span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small fw-semibold d-block">Total Tagihan</label>
                                <span class="fw-extrabold text-dark fs-5 fw-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Slot Jam -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Rincian Slot Waktu Reservasi</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered text-center align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Harga Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->items as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="fw-bold">{{ substr($item->start_time, 0, 5) }}</td>
                                    <td class="fw-bold">{{ substr($item->end_time, 0, 5) }}</td>
                                    <td class="text-end pe-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end pe-3">Total Biaya Akhir:</td>
                                <td class="text-end pe-3 text-emerald fs-6">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Alert Action based on status -->
                @if($booking->payment_status === 'unpaid' && $booking->status === 'pending')
                    <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 rounded-4 shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                            <div>
                                <strong class="d-block text-dark">Pemesanan Belum Dibayar</strong>
                                <span class="small text-muted">Selesaikan pembayaran sebelum pukul {{ $booking->expired_at->format('H:i') }} WIB.</span>
                            </div>
                        </div>
                        <a href="{{ route('user.payments.checkout', $booking->booking_code) }}" class="btn btn-warning btn-sm px-3 rounded-pill fw-bold text-nowrap">
                            Bayar Sekarang
                        </a>
                    </div>
                @elseif($booking->payment_status === 'pending_verification')
                    <div class="alert alert-info d-flex align-items-center gap-2 p-3 rounded-4 shadow-sm">
                        <i class="bi bi-hourglass-split fs-4 text-info"></i>
                        <div>
                            <strong class="d-block text-dark">Bukti Pembayaran Sedang Diverifikasi</strong>
                            <span class="small text-muted">Admin cabang sedang memeriksa transfer Anda. E-Ticket akan aktif setelah disetujui.</span>
                        </div>
                    </div>
                @elseif($booking->status === 'confirmed')
                    <div class="alert alert-success d-flex align-items-center gap-2 p-3 rounded-4 shadow-sm">
                        <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                        <div>
                            <strong class="d-block text-dark">Reservasi Sah & Terkonfirmasi</strong>
                            <span class="small text-muted">Silakan hadir 15 menit sebelum jam main dan tunjukkan tiket ini di meja kasir.</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Ticket Footer Notes -->
            <div class="bg-light p-3 border-top text-center text-muted small" style="font-size: 0.75rem;">
                Harap patuhi tata tertib lapangan, gunakan sepatu olahraga sesuai jenis lantai, dan jaga kebersihan area.
            </div>
        </div>

    </div>
</div>
@endsection
