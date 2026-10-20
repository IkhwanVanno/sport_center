@extends('layouts.admin')

@section('title', 'Edit Fasilitas')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Form Edit -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-dark mb-1">Edit Fasilitas</h5>
                    <p class="text-muted small">Perbarui data fasilitas terpilih</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Fasilitas -->
                        <div class="mb-3">
                            <label for="name" class="form-label text-secondary small fw-semibold">Nama Fasilitas <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $facility->name) }}" placeholder="Contoh: WiFi Gratis, Ruang Ganti" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Class Icon -->
                        <div class="mb-3">
                            <label for="icon" class="form-label text-secondary small fw-semibold">Class Icon <span class="text-muted font-normal">(Opsional)</span></label>
                            <input type="text" id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $facility->icon) }}" placeholder="Contoh: bi bi-wifi">
                            <div class="form-text small">Gunakan class icon dari Bootstrap Icons (bi bi-*)</div>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-end gap-2 pt-2">
                            <a href="{{ route('admin.facilities.index') }}" class="btn btn-light border font-semibold">Batal</a>
                            <button type="submit" class="btn btn-primary font-semibold">Perbarui Fasilitas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Fasilitas Sampingan (Memanfaatkan $facilities dari controller) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">Semua Daftar Fasilitas</h6>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-secondary">
                            <thead class="bg-light small">
                                <tr>
                                    <th class="ps-3 py-2">Icon</th>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2">Class Icon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($facilities as $item)
                                    <tr class="{{ $item->id == $facility->id ? 'table-primary' : '' }}">
                                        <td class="ps-3"><i class="{{ $item->icon ?? 'bi bi-check-circle' }} text-primary fs-6"></i></td>
                                        <td class="fw-semibold text-dark">
                                            {{ $item->name }}
                                            @if($item->id == $facility->id)
                                                <span class="badge bg-primary ms-1">Sedang Di-edit</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $item->icon ?? '-' }}</code></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted small">Belum ada fasilitas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection