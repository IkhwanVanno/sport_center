@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Ringkasan Statistik</h1>
            <p class="text-muted small mb-0">Gambaran performa operasional dan pendapatan sewa lapangan terkini.</p>
        </div>
    </div>

    <!-- Quick Metric Cards -->
    <div class="row g-3">
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card card-custom p-4 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Total Pendapatan</span>
                    <span class="p-2 rounded-circle bg-emerald-subtle text-emerald"><i class="bi bi-wallet2 fs-5"></i></span>
                </div>
                <div class="fs-4 fw-extrabold text-emerald fw-bold mb-1">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                <div class="text-muted small">Akumulasi pembayaran lunas</div>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card card-custom p-4 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Booking Hari Ini</span>
                    <span class="p-2 rounded-circle bg-indigo-subtle text-indigo"><i class="bi bi-calendar-event fs-5"></i></span>
                </div>
                <div class="display-6 fw-bold text-indigo mb-1">{{ $todayBookings ?? 0 }}</div>
                <div class="text-muted small">Transaksi jadwal hari ini</div>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card card-custom p-4 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Verifikasi Transfer</span>
                    <span class="p-2 rounded-circle bg-warning-subtle text-warning"><i class="bi bi-hourglass-split fs-5"></i></span>
                </div>
                <div class="display-6 fw-bold text-warning mb-1">{{ $pendingVerifications ?? 0 }}</div>
                <div class="text-muted small">Memerlukan pengecekan bukti</div>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card card-custom p-4 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Lapangan Aktif</span>
                    <span class="p-2 rounded-circle bg-dark-subtle text-dark"><i class="bi bi-trophy fs-5"></i></span>
                </div>
                <div class="display-6 fw-bold text-dark mb-1">{{ $activeCourts ?? 0 }}</div>
                <div class="text-muted small">Unit siap disewa publik</div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-bottom border-light p-3 p-md-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-0">Transaksi Reservasi Terbaru</h5>
                <p class="text-muted small mb-0">5 pesanan terakhir yang masuk ke dalam sistem</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                Semua Pesanan &rarr;
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Pelanggan</th>
                        <th>Lapangan</th>
                        <th>Tanggal Main</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings ?? [] as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $booking) }}" class="fw-bold text-indigo text-decoration-none font-monospace">
                                    {{ $booking->booking_code }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $booking->user->name ?? 'User' }}</div>
                                <div class="text-muted small">{{ $booking->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $booking->court->name ?? 'Lapangan' }}</span>
                            </td>
                            <td>
                                <span class="text-dark">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</span>
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
                                <a href="{{ route('admin.orders.show', $booking) }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-semibold">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-5 text-center text-muted">
                                Belum ada transaksi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection