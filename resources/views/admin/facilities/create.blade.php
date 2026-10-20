@extends('layouts.admin')

@section('title', 'Tambah Fasilitas Baru')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Form Tambah -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-dark mb-1">Tambah Fasilitas Baru</h5>
                    <p class="text-muted small">Isi formulir berikut untuk menambahkan fasilitas</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.facilities.store') }}" method="POST">
                        @csrf

                        <!-- Nama Fasilitas -->
                        <div class="mb-3">
                            <label for="name" class="form-label text-secondary small fw-semibold">Nama Fasilitas <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: WiFi Gratis, Ruang Ganti" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Class Icon -->
                        <div class="mb-3">
                            <label for="icon" class="form-label text-secondary small fw-semibold">Class Icon <span class="text-muted font-normal">(Opsional)</span></label>
                            <input type="text" id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon') }}" placeholder="Contoh: bi bi-wifi">
                            <div class="form-text small">Gunakan class icon dari Bootstrap Icons (bi bi-*)</div>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-end gap-2 pt-2">
                            <a href="{{ route('admin.facilities.index') }}" class="btn btn-light border font-semibold">Batal</a>
                            <button type="submit" class="btn btn-primary font-semibold">Simpan Fasilitas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Fasilitas Sampingan (Memanfaatkan $facilities dari controller) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">Fasilitas yang Sudah Ada</h6>
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
                                @forelse ($facilities as $facility)
                                    <tr>
                                        <td class="ps-3"><i class="{{ $facility->icon ?? 'bi bi-check-circle' }} text-primary fs-6"></i></td>
                                        <td class="fw-semibold text-dark">{{ $facility->name }}</td>
                                        <td><code>{{ $facility->icon ?? '-' }}</code></td>
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