@extends('layouts.operator')

@section('title', 'Jadwal Penggunaan Lapangan')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header & Date Filter -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Jadwal Penggunaan Lapangan</h1>
            <p class="text-muted small mb-0">Pantau ketersediaan seluruh slot lapangan secara real-time pada cabang Anda.</p>
        </div>

        <form method="GET" action="{{ route('operator.schedule.index') }}" class="d-flex align-items-center gap-2">
            <label for="date" class="small fw-semibold text-secondary text-nowrap">Pilih Tanggal:</label>
            <input type="date" 
                   id="date" 
                   name="date" 
                   value="{{ $date }}" 
                   class="form-control form-control-sm border-emerald" 
                   onchange="this.form.submit()">
        </form>
    </div>

    <!-- Quick Legend Card -->
    <div class="card card-custom p-3 border-0 shadow-sm">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex flex-wrap gap-3 small">
                <div class="d-flex align-items-center gap-1.5">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Tersedia</span>
                    <span class="text-muted">Siap dibooking</span>
                </div>
                <div class="d-flex align-items-center gap-1.5">
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Diproses</span>
                    <span class="text-muted">Menunggu bayar (15m)</span>
                </div>
                <div class="d-flex align-items-center gap-1.5">
                    <span class="badge bg-primary text-white px-2 py-1">Terisi</span>
                    <span class="text-muted">Lunas / Terkonfirmasi</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('operator.walkin.index') }}" class="btn btn-emerald btn-sm rounded-pill px-3 fw-semibold">
                    + Input Walk-In
                </a>
            </div>
        </div>
    </div>

    <!-- Schedule Matrix Across Courts -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-bottom border-light p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-calendar-event text-emerald me-1"></i>
                    Matriks Jadwal: {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                </h6>
                <span class="badge bg-light text-muted border small">{{ count($courts) }} Lapangan</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 180px;" class="text-start ps-3 py-2.5">Lapangan</th>
                        @php
                            $hours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'];
                        @endphp
                        @foreach($hours as $hr)
                            <th style="min-width: 70px;">{{ $hr }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($courts as $court)
                        @php
                            $courtSlots = collect($scheduleData[$court->id]['slots'] ?? [])->keyBy('start_time');
                        @endphp
                        <tr>
                            <td class="text-start ps-3 py-3">
                                <div class="fw-bold text-dark">{{ $court->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $court->sport->name ?? 'Sport' }}</div>
                            </td>

                            @foreach($hours as $hr)
                                @php
                                    $slot = $courtSlots->get($hr);
                                    $status = $slot['status'] ?? 'available';
                                @endphp
                                <td>
                                    @if($status === 'available')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle w-100 py-1.5 font-monospace" title="Tersedia (Rp {{ number_format($slot['price_per_hour'] ?? $court->base_price_per_hour, 0, ',', '.') }})">
                                            KOSONG
                                        </span>
                                    @elseif($status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle w-100 py-1.5" title="Sedang diproses pemesanan">
                                            HOLD
                                        </span>
                                    @else
                                        <span class="badge bg-primary text-white w-100 py-1.5" title="Sudah Dipesan">
                                            TERISI
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" class="p-4 text-muted">
                                Tidak ada data lapangan yang aktif di cabang ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
