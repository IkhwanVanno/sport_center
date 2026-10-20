@extends('layouts.admin')

@section('title', 'Daftar Lapangan Olahraga')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header & Action -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Daftar Lapangan Olahraga</h1>
            <p class="text-muted small mb-0">Kelola unit lapangan fisik, penugasan cabang, jenis olahraga, dan tarif standar sewa per jam.</p>
        </div>
        <a href="{{ route('admin.courts.create') }}" class="btn btn-indigo px-4 py-2.5 rounded-3 fw-semibold small shadow-sm d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            <span>+ Tambah Lapangan Baru</span>
        </a>
    </div>

    <!-- Courts Table Card -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Nama Lapangan</th>
                        <th>Cabang</th>
                        <th>Jenis Olahraga</th>
                        <th>Tarif Standar / Jam</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courts as $court)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $court->name }}</div>
                                <div class="text-muted small font-monospace">slug: {{ $court->slug }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-geo-alt text-danger me-1"></i>{{ $court->branch->name ?? 'Cabang' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle fw-semibold">
                                    {{ $court->sport->name ?? 'Olahraga' }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-emerald fs-6">Rp {{ number_format($court->base_price_per_hour, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <span class="badge {{ $court->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2.5 py-1 rounded-pill small fw-semibold">
                                    {{ $court->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('courts.show', $court->slug) }}" class="btn btn-outline-secondary btn-sm px-2.5 rounded-pill" title="Lihat di Web Publik" target="_blank">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="{{ route('admin.courts.edit', $court) }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini? Seluruh riwayat harga & booking terkait mungkin terpengaruh.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-5 text-center text-muted">
                                Data lapangan belum tersedia. Klik tombol tambah di atas untuk membuat lapangan pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courts->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $courts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection