@extends('layouts.admin')

@section('title', 'Aturan Tarif')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header & Action -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Aturan Tarif Dinamis (Peak Hours)</h1>
            <p class="text-muted small mb-0">Kelola penyesuaian harga khusus berdasarkan hari dan rentang jam sewa.</p>
        </div>
        <a href="{{ route('admin.price-rules.create') }}" class="btn btn-indigo px-4 py-2.5 rounded-3 fw-semibold small shadow-sm d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Aturan Tarif</span>
        </a>
    </div>

    <!-- Table Card -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Lapangan & Cabang</th>
                        <th>Hari Berlaku</th>
                        <th>Rentang Jam</th>
                        <th>Tarif Khusus / Jam</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; 
                    @endphp
                    @forelse($priceRules as $rule)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $rule->court->name ?? 'Lapangan' }}</div>
                                <div class="text-muted small">
                                    <i class="bi bi-geo-alt text-danger me-1"></i>{{ $rule->court->branch->name ?? 'Cabang' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-semibold">
                                    <i class="bi bi-calendar-day text-indigo me-1"></i>{{ $days[$rule->day_of_week] ?? $rule->day_of_week }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle font-monospace px-2.5 py-1">
                                    {{ substr($rule->start_time, 0, 5) }} - {{ substr($rule->end_time, 0, 5) }} WIB
                                </span>
                            </td>
                            <td>
                                <strong class="text-emerald fs-6">Rp {{ number_format($rule->price_per_hour, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.price-rules.destroy', $rule) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan tarif khusus ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold" title="Hapus">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-5 text-center text-muted">
                                Belum ada aturan tarif khusus. Klik tombol tambah di atas untuk membuat aturan tarif pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($priceRules->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $priceRules->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection