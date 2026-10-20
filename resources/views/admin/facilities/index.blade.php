@extends('layouts.admin')

@section('title', 'Fasilitas')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Page & Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Daftar Fasilitas</h4>
            <p class="text-muted small mb-0">Kelola master fasilitas pendukung untuk lapangan olahraga</p>
        </div>
        <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary fw-semibold px-3 py-2">
            <i class="bi bi-plus-lg me-1"></i>Tambah Fasilitas
        </a>
    </div>

    <!-- Card Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-secondary">
                    <thead class="bg-light text-uppercase text-dark small font-monospace">
                        <tr>
                            <th class="ps-4 py-3" style="width: 5%;">No</th>
                            <th class="py-3" style="width: 10%;">Icon</th>
                            <th class="py-3">Nama Fasilitas</th>
                            <th class="py-3">Class Icon</th>
                            <th class="pe-4 py-3 text-end" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilities as $index => $facility)
                            <tr>
                                <td class="ps-4 font-monospace">{{ $index + 1 }}</td>
                                <td>
                                    <div class="bg-light rounded-3 d-inline-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                                        @if ($facility->icon)
                                            <i class="{{ $facility->icon }} fs-5"></i>
                                        @else
                                            <i class="bi bi-check-circle fs-5"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="fw-semibold text-dark">{{ $facility->name }}</td>
                                <td><code>{{ $facility->icon ?? '-' }}</code></td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.facilities.edit', $facility->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Belum ada data fasilitas yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection