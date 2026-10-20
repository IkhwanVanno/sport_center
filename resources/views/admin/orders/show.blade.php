@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $booking->booking_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">

        <!-- Header & Back Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold mb-2">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Pesanan
                </a>
                <h1 class="h3 fw-extrabold text-dark mb-0">Detail Pesanan: <span class="font-monospace text-indigo">{{ $booking->booking_code }}</span></h1>
            </div>

            <div class="d-flex gap-2">
                <x-badge-status :status="$booking->status" type="booking" />
                <x-badge-status :status="$booking->payment_status" type="payment" />
            </div>
        </div>

        <!-- Verification Banner if pending_verification -->
        @if($booking->payment_status === 'pending_verification')
            <div class="card card-custom p-4 border-0 shadow-sm mb-4 bg-warning-subtle border-warning-subtle">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Memerlukan Verifikasi Pembayaran Manual</h5>
                            <p class="text-secondary small mb-0">Pelanggan telah mengunggah bukti transfer. Silakan periksa mutasi rekening sebelum mengonfirmasi.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.orders.confirm-payment', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengonfirmasi lunas pembayaran pesanan ini?');">
                        @csrf
                        <button type="submit" class="btn btn-emerald px-4 py-2.5 rounded-3 fw-bold shadow-sm text-nowrap">
                            <i class="bi bi-check-circle-fill me-1"></i>Konfirmasi Lunas
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <!-- Customer Info -->
            <div class="col-md-6">
                <div class="card card-custom p-4 border-0 shadow-sm h-100">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-person text-indigo"></i>
                        <span>Informasi Pemesan</span>
                    </h6>
                    <table class="table table-sm table-borderless small mb-0">
                        <tr>
                            <td class="text-muted" style="width: 130px;">Nama Pelanggan</td>
                            <td class="fw-bold text-dark">{{ $booking->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor WhatsApp</td>
                            <td class="fw-semibold text-dark">{{ $booking->user->phone_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat Email</td>
                            <td>{{ $booking->user->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Waktu Transaksi</td>
                            <td>{{ $booking->created_at->format('d M Y, H:i:s') }} WIB</td>
                        </tr>
                        @if($booking->notes)
                            <tr>
                                <td class="text-muted">Catatan Khusus</td>
                                <td class="text-secondary fst-italic">"{{ $booking->notes }}"</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Court & Schedule Info -->
            <div class="col-md-6">
                <div class="card card-custom p-4 border-0 shadow-sm h-100">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt text-danger"></i>
                        <span>Lapangan & Jadwal</span>
                    </h6>
                    <table class="table table-sm table-borderless small mb-0">
                        <tr>
                            <td class="text-muted" style="width: 130px;">Nama Lapangan</td>
                            <td class="fw-bold text-dark">{{ $booking->court->name ?? 'Lapangan' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cabang Fasilitas</td>
                            <td>{{ $booking->court->branch->name ?? 'Cabang' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Main</td>
                            <td class="fw-bold text-emerald">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Pembayaran</td>
                            <td class="fw-extrabold text-dark fs-5 fw-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Booked Items Breakdown -->
        <div class="card card-custom border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom border-light p-3">
                <h6 class="fw-bold text-dark mb-0">Rincian Slot Jam Sewa</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered text-center align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Snapshot Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-bold">{{ substr($item->start_time, 0, 5) }}</td>
                                <td class="fw-bold">{{ substr($item->end_time, 0, 5) }}</td>
                                <td class="text-end pe-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-end pe-4">Total:</td>
                            <td class="text-end pe-4 text-emerald fs-6">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment & Proof Section -->
        <div class="card card-custom p-4 border-0 shadow-sm mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Catatan Pembayaran & Bukti Transfer</h6>

            @php
                $latestPayment = $booking->payments->last();
            @endphp

            @if($latestPayment && $latestPayment->proof_of_payment)
                <div class="row g-4 align-items-center">
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $latestPayment->proof_of_payment) }}" target="_blank" title="Klik untuk memperbesar">
                            <img src="{{ asset('storage/' . $latestPayment->proof_of_payment) }}" 
                                 alt="Bukti Transfer {{ $booking->booking_code }}" 
                                 class="img-fluid rounded-3 border shadow-sm" 
                                 style="max-height: 250px;">
                        </a>
                        <div class="text-muted small mt-1">Klik gambar untuk melihat ukuran penuh</div>
                    </div>

                    <div class="col-md-7">
                        <table class="table table-sm table-borderless small mb-3">
                            <tr>
                                <td class="text-muted" style="width: 140px;">Metode Pembayaran</td>
                                <td class="fw-bold text-uppercase">{{ $latestPayment->payment_method }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nominal Tercatat</td>
                                <td class="fw-bold text-dark">Rp {{ number_format($latestPayment->amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Waktu Upload Bukti</td>
                                <td>{{ $latestPayment->created_at->format('d M Y, H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status Transaksi</td>
                                <td><x-badge-status :status="$latestPayment->status" type="payment" /></td>
                            </tr>
                        </table>

                        @if($booking->payment_status === 'pending_verification')
                            <form action="{{ route('admin.orders.confirm-payment', $booking) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-emerald px-4 py-2 rounded-3 fw-bold">
                                    <i class="bi bi-check-circle me-1"></i>Verifikasi Bukti & Nyatakan Lunas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-4 text-center text-muted bg-light rounded-4">
                    <i class="bi bi-receipt-cutoff fs-2 d-block mb-1"></i>
                    <p class="small mb-0">Belum ada file bukti transfer fisik yang diunggah untuk transaksi ini (Metode: {{ $latestPayment->payment_method ?? 'Online / Kasir' }}).</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection