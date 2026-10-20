@extends('layouts.admin')

@section('title', 'Cabang Olahraga')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header & Action -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Daftar Cabang Olahraga</h1>
            <p class="text-muted small mb-0">Kelola master jenis olahraga, visual icon, dan korelasi unit lapangan aktif.</p>
        </div>
        <a href="{{ route('admin.sports.create') }}" class="btn btn-indigo px-4 py-2.5 rounded-3 fw-semibold small shadow-sm d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Olahraga Baru</span>
        </a>
    </div>

    <!-- Sports Table Card -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <!-- Responsive wrapper using Tailwind overflow-x-auto & Bootstrap table-responsive -->
        <div class="table-responsive overflow-x-auto w-full">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">No</th>
                        <th style="width: 10%;">Icon</th>
                        <th>Nama Olahraga</th>
                        <th>Slug Identifikasi</th>
                        <th>Total Lapangan</th>
                        <th class="text-end" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sports as $index => $sport)
                        <tr>
                            <td class="text-center font-monospace text-muted small">
                                {{ $sports->firstItem() ? $sports->firstItem() + $index : $index + 1 }}
                            </td>
                            <td>
                                <div class="bg-indigo-subtle text-indigo rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    @if($sport->icon)
                                        @if(str_contains($sport->icon, 'bi-') || str_starts_with($sport->icon, 'bi '))
                                            <i class="{{ $sport->icon }} fs-5"></i>
                                        @else
                                            <span class="small fw-bold">{{ Str::limit($sport->icon, 4) }}</span>
                                        @endif
                                    @else
                                        <i class="bi bi-dribbble fs-5"></i>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $sport->name }}</div>
                                @if($sport->icon)
                                    <div class="text-muted small font-monospace"><i class="bi bi-code me-1"></i>{{ $sport->icon }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border font-monospace px-2.5 py-1.5">
                                    {{ $sport->slug }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                    <i class="bi bi-trophy me-1"></i>{{ $sport->courts_count ?? 0 }} Unit
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.sports.edit', $sport) }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.sports.destroy', $sport) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis olahraga &quot;{{ $sport->name }}&quot;? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold" title="Hapus Olahraga">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-5 text-center text-muted">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-inbox fs-1 text-secondary mb-2"></i>
                                    <span class="fw-semibold">Belum ada data cabang olahraga.</span>
                                    <span class="small text-muted mt-1">Silakan klik tombol "Tambah Olahraga Baru" untuk mulai menambahkan jenis olahraga pertama Anda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sports->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $sports->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
