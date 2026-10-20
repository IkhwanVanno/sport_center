@extends('layouts.admin')

@section('title', 'Tambah Lapangan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Breadcrumb & Back -->
        <div class="mb-3">
            <a href="{{ route('admin.courts.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Lapangan
            </a>
        </div>

        <div class="card card-custom p-4 border-0 shadow-sm">
            <h4 class="fw-bold text-dark border-bottom pb-3 mb-4">Tambah Lapangan Baru</h4>

            <form action="{{ route('admin.courts.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <!-- Cabang -->
                    <div class="col-md-6">
                        <label for="branch_id" class="form-label small fw-semibold text-secondary">Lokasi Cabang <span class="text-danger">*</span></label>
                        <select name="branch_id" id="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Olahraga -->
                    <div class="col-md-6">
                        <label for="sport_id" class="form-label small fw-semibold text-secondary">Jenis Olahraga <span class="text-danger">*</span></label>
                        <select name="sport_id" id="sport_id" class="form-select @error('sport_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Olahraga --</option>
                            @foreach($sports as $sport)
                            <option value="{{ $sport->id }}" {{ old('sport_id') == $sport->id ? 'selected' : '' }}>
                                {{ $sport->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('sport_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Nama Lapangan -->
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Nama Lapangan <span class="text-danger">*</span></label>
                    <input type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Contoh: Lapangan Futsal A (Rumput Sintetis)"
                        required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga Dasar per Jam -->
                <div class="mb-3">
                    <label for="base_price_per_hour" class="form-label small fw-semibold text-secondary">Harga Dasar Sewa per Jam (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary">Rp</span>
                        <input type="number"
                            id="base_price_per_hour"
                            name="base_price_per_hour"
                            value="{{ old('base_price_per_hour') }}"
                            class="form-control @error('base_price_per_hour') is-invalid @enderror"
                            placeholder="120000"
                            min="0"
                            step="1000"
                            required>
                    </div>
                    @error('base_price_per_hour')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- fasilitas -->
                <div class="mb-3">
                    <label for="facilities" class="form-label small fw-semibold text-secondary">Fasilitas Tambahan</label>
                    @foreach ($facilities as $facility)
                    <div class="form-check">
                        <input type="checkbox" name="facilities[]" id="facility_{{ $facility->id }}" value="{{ $facility->id }}" class="form-check-input" {{ (collect(old('facilities'))->contains($facility->id)) ? 'checked' : '' }}>
                        <label for="facility_{{ $facility->id }}" class="form-check-label">{{ $facility->name }}</label>
                    </div>
                    @endforeach
                    @error('facilities')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold text-secondary">Deskripsi & Spesifikasi Lapangan</label>
                    <textarea id="description"
                        name="description"
                        rows="3"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Contoh: Lantai karpet standar BWF, pencahayaan LED terang, jaring keliling baru.">{{ old('description') }}</textarea>
                    @error('description')
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
                        Status Aktif & Siap Disewa Publik
                    </label>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.courts.index') }}" class="btn btn-light border px-4 py-2 rounded-3 fw-semibold">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-indigo px-4 py-2 rounded-3 fw-bold shadow-sm">
                        Simpan Lapangan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection