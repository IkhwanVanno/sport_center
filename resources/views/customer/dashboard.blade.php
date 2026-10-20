@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header Page & Action -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Dashboard Pelanggan</h1>
            <p class="text-muted small mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>! Kelola reservasi lapangan Anda di sini.</p>
        </div>
        <a href="{{ route('courts.index') }}" class="btn btn-emerald px-4 py-2.5 rounded-3 shadow-sm fw-bold small d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            <span>Pesan Lapangan Baru</span>
        </a>
    </div>

    <!-- Quick Metric Cards -->
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="card card-custom p-4 h-100 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Total Pemesanan</span>
                    <span class="p-2 rounded-circle bg-indigo-subtle text-indigo"><i class="bi bi-calendar-check fs-5"></i></span>
                </div>
                <div class="display-6 fw-bold text-indigo mb-1">{{ $totalBookings ?? 0 }}</div>
                <div class="text-muted small">Transaksi seumur hidup</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-custom p-4 h-100 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Jadwal Main Mendatang</span>
                    <span class="p-2 rounded-circle bg-emerald-subtle text-emerald"><i class="bi bi-clock-history fs-5"></i></span>
                </div>
                <div class="display-6 fw-bold text-emerald mb-1">{{ $upcomingBookings ?? 0 }}</div>
                <div class="text-muted small">Booking aktif terkonfirmasi</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-custom p-4 h-100 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Menunggu Pembayaran</span>
                    <span class="p-2 rounded-circle bg-warning-subtle text-warning"><i class="bi bi-credit-card-2-front fs-5"></i></span>
                </div>
                <div class="display-6 fw-bold text-warning mb-1">{{ $pendingPayment ?? 0 }}</div>
                <div class="text-muted small">Harus dibayar sebelum kedaluwarsa</div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-0">Riwayat Pemesanan Terbaru</h5>
                <p class="text-muted small mb-0">Daftar transaksi reservasi terakhir milik Anda</p>
            </div>
            <a href="{{ route('user.bookings.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold rounded-pill px-3">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Lapangan & Cabang</th>
                        <th>Tanggal & Jam Main</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings ?? [] as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="fw-bold text-indigo text-decoration-none">
                                    {{ $booking->booking_code }}
                                </a>
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ $booking->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $booking->court->name ?? 'Lapangan' }}</div>
                                <div class="text-muted small">{{ $booking->court->branch->name ?? 'Sport Center' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</div>
                                <div class="text-muted small">
                                    @if($booking->items->isNotEmpty())
                                        {{ substr($booking->items->min('start_time'), 0, 5) }} - {{ substr($booking->items->max('end_time'), 0, 5) }} ({{ $booking->items->count() }} Jam)
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <x-badge-status :status="$booking->status" type="booking" />
                                    <x-badge-status :status="$booking->payment_status" type="payment" />
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    @if($booking->payment_status === 'unpaid' && $booking->status === 'pending' && $booking->expired_at->isFuture())
                                        <a href="{{ route('user.payments.checkout', $booking->booking_code) }}" class="btn btn-emerald btn-sm px-3 rounded-pill fw-semibold">
                                            Bayar
                                        </a>
                                    @elseif($booking->payment_status === 'pending_verification')
                                        <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="btn btn-outline-warning btn-sm px-3 rounded-pill fw-semibold">
                                            Diproses
                                        </a>
                                    @else
                                        <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
                                            E-Ticket
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-5 text-center text-muted">
                                <div class="mb-2"><i class="bi bi-inbox fs-2"></i></div>
                                <p class="mb-2 fw-semibold">Anda belum memiliki riwayat pemesanan.</p>
                                <a href="{{ route('courts.index') }}" class="btn btn-emerald btn-sm px-3 py-1.5 rounded-pill">
                                    Mulai Pesan Lapangan
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection