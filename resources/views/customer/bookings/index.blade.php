@extends('layouts.customer')

@section('title', 'Riwayat Pemesanan Lapangan')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header & Breadcrumb -->
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active text-emerald fw-semibold" aria-current="page">Riwayat Pemesanan</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <h1 class="h3 fw-extrabold text-dark mb-1">Riwayat Pemesanan Lapangan</h1>
                <p class="text-muted small mb-0">Semua transaksi sewa lapangan yang pernah Anda lakukan.</p>
            </div>
            <a href="{{ route('courts.index') }}" class="btn btn-emerald btn-sm px-4 py-2 rounded-3 fw-semibold">
                + Sewa Lapangan Baru
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card card-custom p-3 border-0 shadow-sm">
        <form method="GET" action="{{ route('user.bookings.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4 col-sm-6">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status Booking</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi / Lunas</option>
                    <option value="in_use" {{ request('status') === 'in_use' ? 'selected' : '' }}>Sedang Bermain</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan / Batal</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                </select>
            </div>

            <div class="col-md-4 col-sm-6">
                <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="pending_verification" {{ request('payment_status') === 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div class="col-md-4 text-md-end">
                @if(request()->hasAny(['status', 'payment_status']))
                    <a href="{{ route('user.bookings.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Lapangan & Cabang</th>
                        <th>Tanggal Main</th>
                        <th>Durasi & Jam</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="fw-bold text-indigo text-decoration-none">
                                    {{ $booking->booking_code }}
                                </a>
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $booking->court->name ?? 'Lapangan' }}</div>
                                <div class="text-muted small">{{ $booking->court->branch->name ?? 'Cabang' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td>
                                @if($booking->items->isNotEmpty())
                                    <span class="badge bg-light text-dark border">
                                        {{ substr($booking->items->min('start_time'), 0, 5) }} - {{ substr($booking->items->max('end_time'), 0, 5) }}
                                    </span>
                                    <div class="text-muted small mt-0.5">{{ $booking->items->count() }} Jam</div>
                                @endif
                            </td>
                            <td>
                                <strong class="text-dark">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
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
                                            Status
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
                            <td colspan="7" class="p-5 text-center text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2 text-muted"></i>
                                <p class="fw-semibold mb-2">Tidak ditemukan data booking yang sesuai.</p>
                                <a href="{{ route('courts.index') }}" class="btn btn-emerald btn-sm px-4 py-2 rounded-pill">
                                    Cari Lapangan Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
