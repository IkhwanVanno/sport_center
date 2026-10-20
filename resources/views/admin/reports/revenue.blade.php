@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header & Action -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Laporan Omzet Pendapatan</h1>
            <p class="text-muted small mb-0">Rekapitulasi transaksi sewa lapangan yang berstatus lunas / terkonfirmasi.</p>
        </div>

        <button type="button" onclick="window.print()" class="btn btn-outline-dark btn-sm px-3 rounded-pill fw-semibold no-print d-flex align-items-center gap-1">
            <i class="bi bi-printer"></i>
            <span>Cetak / Ekspor Laporan</span>
        </button>
    </div>

    <!-- Filter Card -->
    <div class="card card-custom p-3 p-md-4 border-0 shadow-sm no-print">
        <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3 align-items-end">
            <div class="col-12 col-sm-6 col-md-3">
                <label for="start_date" class="form-label small fw-semibold text-secondary">Dari Tanggal</label>
                <input type="date" 
                       id="start_date" 
                       name="start_date" 
                       value="{{ $startDate }}" 
                       class="form-control form-control-sm">
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label for="end_date" class="form-label small fw-semibold text-secondary">Sampai Tanggal</label>
                <input type="date" 
                       id="end_date" 
                       name="end_date" 
                       value="{{ $endDate }}" 
                       class="form-control form-control-sm">
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <label for="branch_id" class="form-label small fw-semibold text-secondary">Filter Cabang</label>
                @if(auth()->user()->isSuperAdmin())
                    <select name="branch_id" id="branch_id" class="form-select form-select-sm">
                        <option value="">Semua Cabang Fasilitas (Konsolidasi)</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Cabang</span>
                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->branch->name ?? 'Cabang Anda' }}" readonly disabled>
                    </div>
                @endif
            </div>

            <div class="col-12 col-sm-6 col-md-2">
                <button type="submit" class="btn btn-indigo btn-sm w-100 fw-semibold py-2">
                    <i class="bi bi-filter me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Revenue Summary Banner -->
    <div class="card card-custom border-0 shadow-sm p-4 bg-emerald-subtle border-emerald-subtle">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <span class="text-uppercase fw-bold text-success small" style="font-size: 0.75rem;">Total Omzet Terverifikasi (Lunas)</span>
                <div class="display-6 fw-extrabold text-emerald fw-bold mt-1">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
            </div>
            <div class="text-sm-end text-muted small">
                Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
                <div class="text-secondary">{{ count($reports) }} Transaksi Berhasil</div>
            </div>
        </div>
    </div>

    <!-- Revenue Data Table -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Tanggal Main</th>
                        <th>Pelanggan</th>
                        <th>Lapangan / Cabang</th>
                        <th class="text-end">Nominal Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $report) }}" class="fw-bold text-indigo text-decoration-none font-monospace">
                                    {{ $report->booking_code }}
                                </a>
                            </td>
                            <td>
                                <span class="text-dark">{{ \Carbon\Carbon::parse($report->booking_date)->translatedFormat('d M Y') }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $report->user->name ?? 'User' }}</div>
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ $report->user->phone_number ?? $report->user->email }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $report->court->name ?? 'Lapangan' }}</div>
                                <div class="text-muted small">{{ $report->court->branch->name ?? 'Cabang' }}</div>
                            </td>
                            <td class="text-end">
                                <strong class="text-dark">Rp {{ number_format($report->total_amount, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-5 text-center text-muted">
                                Tidak ada data transaksi lunas pada periode tanggal yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($reports) > 0)
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="4" class="text-end pe-3">Total Pendapatan Terhitung:</td>
                            <td class="text-end text-emerald fs-6">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection