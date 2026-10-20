@extends('layouts.operator')

@section('title', 'Pemeriksaan & Verifikasi Check-In')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">

        <!-- Header -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-extrabold text-dark mb-1">Check-In & Verifikasi Lapangan</h1>
                <p class="text-muted small mb-0">Cari kode booking e-ticket pelanggan untuk verifikasi kehadiran di lokasi.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('operator.walkin.index') }}" class="btn btn-emerald btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-plus-circle me-1"></i>+ Walk-In Kasir
                </a>
                <a href="{{ route('operator.schedule.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-calendar3 me-1"></i>Jadwal Hari Ini
                </a>
            </div>
        </div>

        <!-- Search Box Card -->
        <div class="card card-custom p-4 border-0 shadow-sm mb-4 bg-slate-900 text-white">
            <h5 class="fw-bold mb-3 text-white d-flex align-items-center gap-2">
                <i class="bi bi-qr-code-scan text-indigo fs-5"></i>
                <span>Cari Kode Booking / Scan QR Code</span>
            </h5>

            <form method="GET" action="{{ route('operator.checkin.index') }}" class="row g-2">
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-text bg-slate-800 text-secondary border-0"><i class="bi bi-search"></i></span>
                        <input type="text" 
                               name="code" 
                               value="{{ request('code') }}" 
                               class="form-control form-control-lg bg-slate-800 text-white border-0 font-monospace" 
                               placeholder="Contoh: BK-261019-XXXXXX" 
                               required 
                               autofocus>
                    </div>
                </div>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-indigo btn-lg w-100 fw-bold">
                        Periksa Kode
                    </button>
                </div>
            </form>
            <div class="text-secondary small mt-2">
                <i class="bi bi-info-circle me-1"></i>Masukkan kode booking dari smartphone pelanggan atau gunakan barcode scanner USB.
            </div>
        </div>

        <!-- Booking Result Card -->
        @if(isset($booking) && $booking)
            <div class="card card-custom p-4 border-0 shadow-sm mb-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4 gap-2">
                    <div>
                        <span class="badge bg-light text-secondary border small font-monospace">KODE BOOKING</span>
                        <h4 class="fw-bold text-dark font-monospace mb-0">{{ $booking->booking_code }}</h4>
                    </div>
                    <div class="d-flex gap-2">
                        <x-badge-status :status="$booking->status" type="booking" />
                        <x-badge-status :status="$booking->payment_status" type="payment" />
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3">Informasi Pelanggan</h6>
                        <table class="table table-sm table-borderless small mb-0">
                            <tr>
                                <td class="text-muted" style="width: 120px;">Nama Pemesan</td>
                                <td class="fw-bold text-dark">{{ $booking->user->name ?? 'Pelanggan' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nomor WhatsApp</td>
                                <td class="fw-semibold text-dark">{{ $booking->user->phone_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email Akun</td>
                                <td>{{ $booking->user->email ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3">Informasi Lapangan</h6>
                        <table class="table table-sm table-borderless small mb-0">
                            <tr>
                                <td class="text-muted" style="width: 120px;">Lapangan</td>
                                <td class="fw-bold text-dark">{{ $booking->court->name ?? 'Lapangan' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Cabang</td>
                                <td>{{ $booking->court->branch->name ?? 'Sport Center' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Sewa</td>
                                <td class="fw-bold text-emerald">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Biaya</td>
                                <td class="fw-bold text-dark fs-6">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Booked Slots List -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-2">Slot Jam Terdaftar:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($booking->items as $item)
                            <span class="badge bg-light text-dark border p-2 fw-semibold">
                                <i class="bi bi-clock me-1 text-indigo"></i>
                                {{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }} WIB
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Verification Action Form -->
                <div class="border-top pt-4">
                    @if($booking->status === 'confirmed')
                        <form method="POST" action="{{ route('operator.checkin.verify') }}" onsubmit="return confirm('Verifikasi dan izinkan pelanggan masuk lapangan?');">
                            @csrf
                            <input type="hidden" name="booking_code" value="{{ $booking->booking_code }}">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 bg-success-subtle p-3 rounded-4 border border-success-subtle">
                                <div>
                                    <h6 class="fw-bold text-success mb-0"><i class="bi bi-check-circle-fill me-1"></i>Booking Terkonfirmasi & Lunas</h6>
                                    <div class="text-muted small">Pelanggan telah melunasi tagihan dan berhak menggunakan lapangan.</div>
                                </div>
                                <button type="submit" class="btn btn-emerald px-4 py-2.5 rounded-3 fw-bold shadow-sm text-nowrap">
                                    <i class="bi bi-door-open-fill me-1"></i>Verifikasi Check-In (Bermain)
                                </button>
                            </div>
                        </form>
                    @elseif($booking->status === 'in_use')
                        <div class="alert alert-primary d-flex align-items-center gap-3 p-3 rounded-4 mb-0">
                            <i class="bi bi-check2-all fs-2 text-primary"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Status: Sedang Bermain (Checked-In)</h6>
                                <div class="small text-muted">Pelanggan ini sudah di-check-in sebelumnya dan sedang menggunakan lapangan.</div>
                            </div>
                        </div>
                    @elseif($booking->status === 'pending')
                        <div class="alert alert-warning d-flex align-items-center justify-content-between gap-3 p-3 rounded-4 mb-0">
                            <div>
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>Status: Menunggu Pembayaran</h6>
                                <div class="small text-muted">Pelanggan belum menyelesaikan pembayaran atau bukti transfer belum diverifikasi admin.</div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger d-flex align-items-center gap-3 p-3 rounded-4 mb-0">
                            <i class="bi bi-x-circle-fill fs-2 text-danger"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Status: {{ strtoupper($booking->status) }}</h6>
                                <div class="small text-muted">Booking ini tidak valid untuk check-in.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @elseif(request('code'))
            <div class="card card-custom p-5 text-center border-0 shadow-sm mb-4">
                <i class="bi bi-search fs-1 text-danger mb-2"></i>
                <h5 class="fw-bold text-dark mb-1">Kode Booking Tidak Ditemukan</h5>
                <p class="text-muted small mb-0">Tidak ada reservasi dengan kode "<strong>{{ request('code') }}</strong>". Pastikan penulisan kode sudah benar.</p>
            </div>
        @endif

        <!-- Quick Help Cards -->
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card card-custom p-4 border-0 shadow-sm h-100">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-cash-coin text-emerald me-1"></i>Pelanggan Walk-In Tanpa Akun?</h6>
                    <p class="text-muted small mb-3">Gunakan form Walk-In Kasir untuk membuat booking lapangan langsung di tempat dengan pembayaran tunai.</p>
                    <div>
                        <a href="{{ route('operator.walkin.index') }}" class="btn btn-outline-success btn-sm px-3 rounded-pill fw-semibold">
                            Buka Form Walk-In &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-custom p-4 border-0 shadow-sm h-100">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-clock-history text-indigo me-1"></i>Pantau Jadwal Hari Ini</h6>
                    <p class="text-muted small mb-3">Lihat ketersediaan seluruh lapangan cabang hari ini untuk mengecek slot mana yang kosong atau sedang dipakai.</p>
                    <div>
                        <a href="{{ route('operator.schedule.index') }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
                            Lihat Jadwal Harian &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection