@extends('layouts.admin')

@section('title', 'Transaksi')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Daftar Transaksi Pesanan</h1>
            <p class="text-muted small mb-0">Kelola reservasi sewa lapangan, pantau status pembayaran, dan verifikasi transfer manual.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card card-custom p-3 border-0 shadow-sm">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-center">
            @if(auth()->user()->isSuperAdmin())
                <div class="col-md-3 col-sm-6">
                    <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Cabang Sport Center</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ (string) request('branch_id') === (string) $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="col-md-3 col-sm-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Cabang</span>
                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->branch->name ?? 'Cabang Anda' }}" readonly disabled>
                    </div>
                </div>
            @endif

            <div class="col-md-2 col-sm-6">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status Booking</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi / Lunas</option>
                    <option value="in_use" {{ request('status') === 'in_use' ? 'selected' : '' }}>Sedang Bermain</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="pending_verification" {{ request('payment_status') === 'pending_verification' ? 'selected' : '' }}>Perlu Verifikasi (Manual)</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Kode booking / pemesan...">
                    <button type="submit" class="btn btn-indigo"><i class="bi bi-search"></i></button>
                </div>
            </div>

            <div class="col-md-1 text-md-end">
                @if(request()->hasAny(['branch_id', 'status', 'payment_status', 'search']))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                        <i class="bi bi-x-circle"></i>
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
                        <th>Pelanggan</th>
                        <th>Lapangan & Cabang</th>
                        <th>Tanggal Main</th>
                        <th>Total Biaya</th>
                        <th>Status Booking & Bayar</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $booking) }}" class="fw-bold text-indigo text-decoration-none font-monospace">
                                    {{ $booking->booking_code }}
                                </a>
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $booking->user->name ?? 'User' }}</div>
                                <div class="text-muted small">{{ $booking->user->phone_number ?? $booking->user->email }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $booking->court->name ?? 'Lapangan' }}</div>
                                <div class="text-muted small">{{ $booking->court->branch->name ?? 'Cabang' }}</div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</span>
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
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-5 text-center text-muted">
                                Tidak ada transaksi pesanan yang sesuai filter.
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