@extends('layouts.admin')

@section('title', 'Daftar Cabang Olahraga')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header & Action -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Daftar Cabang Fasilitas</h1>
            <p class="text-muted small mb-0">Kelola lokasi operasional, nomor kontak, dan status keaktifan cabang Sport Center.</p>
        </div>
        <a href="{{ route('admin.branches.create') }}" class="btn btn-indigo px-4 py-2.5 rounded-3 fw-semibold small shadow-sm d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            <span>+ Tambah Cabang Baru</span>
        </a>
    </div>

    <!-- Branches Table Card -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Nama Cabang</th>
                        <th>Alamat Lengkap</th>
                        <th>Telepon Kontak</th>
                        <th>Total Lapangan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $branch->name }}</div>
                                <div class="text-muted small font-monospace">slug: {{ $branch->slug }}</div>
                            </td>
                            <td>
                                <div class="small text-secondary" style="max-width: 280px;">{{ $branch->address }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    <i class="bi bi-telephone text-emerald me-1"></i>{{ $branch->phone }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-indigo">{{ $branch->courts_count ?? 0 }} Unit</strong>
                            </td>
                            <td>
                                <span class="badge {{ $branch->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2.5 py-1 rounded-pill small fw-semibold">
                                    {{ $branch->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini? Seluruh data lapangan terkait mungkin terpengaruh.')">
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
                                Data cabang belum tersedia. Klik tombol tambah di atas untuk membuat cabang pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($branches->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $branches->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection