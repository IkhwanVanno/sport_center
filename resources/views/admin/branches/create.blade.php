@extends('layouts.admin')

@section('title', 'Tambah Cabang Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Breadcrumb & Back -->
        <div class="mb-3">
            <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Cabang
            </a>
        </div>

        <div class="card card-custom p-4 border-0 shadow-sm">
            <h4 class="fw-bold text-dark border-bottom pb-3 mb-4">Tambah Cabang Baru</h4>

            <form action="{{ route('admin.branches.store') }}" method="POST">
                @csrf

                <!-- Nama Cabang -->
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Nama Cabang <span class="text-danger">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Contoh: Sport Center MERR Surabaya" 
                           required 
                           autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat Lengkap -->
                <div class="mb-3">
                    <label for="address" class="form-label small fw-semibold text-secondary">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea id="address" 
                              name="address" 
                              rows="3" 
                              class="form-control @error('address') is-invalid @enderror" 
                              placeholder="Contoh: Jl. Dr. Ir. H. Soekarno No. 123, Rungkut, Surabaya" 
                              required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div class="mb-3">
                    <label for="phone" class="form-label small fw-semibold text-secondary">Nomor Telepon Kontak <span class="text-danger">*</span></label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           placeholder="Contoh: 081234567890" 
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Keaktifan -->
                <div class="mb-4 form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label small fw-semibold text-dark" for="is_active">
                        Cabang Aktif Beroperasi
                    </label>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-light border px-4 py-2 rounded-3 fw-semibold">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-indigo px-4 py-2 rounded-3 fw-bold shadow-sm">
                        Simpan Cabang
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection