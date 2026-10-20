@extends('layouts.admin')

@section('title', 'Tambah Aturan Tarif Khusus')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Breadcrumb & Back -->
        <div class="mb-3">
            <a href="{{ route('admin.price-rules.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Tarif
            </a>
        </div>

        <div class="card card-custom p-4 border-0 shadow-sm">
            <h4 class="fw-bold text-dark border-bottom pb-3 mb-4">Tambah Aturan Tarif Khusus (Peak Hour)</h4>

            <form action="{{ route('admin.price-rules.store') }}" method="POST">
                @csrf

                <!-- Lapangan -->
                <div class="mb-3">
                    <label for="court_id" class="form-label small fw-semibold text-secondary">Pilih Lapangan <span class="text-danger">*</span></label>
                    <select name="court_id" id="court_id" class="form-select @error('court_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}" {{ old('court_id') == $court->id ? 'selected' : '' }}>
                                {{ $court->name }} ({{ $court->branch->name ?? 'Cabang' }}) — Standar: Rp {{ number_format($court->base_price_per_hour, 0, ',', '.') }}/jam
                            </option>
                        @endforeach
                    </select>
                    @error('court_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hari Berlaku -->
                <div class="mb-3">
                    <label for="day_of_week" class="form-label small fw-semibold text-secondary">Hari Berlaku <span class="text-danger">*</span></label>
                    <select name="day_of_week" id="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                        <option value="0" {{ old('day_of_week') === '0' ? 'selected' : '' }}>Minggu (Weekend)</option>
                        <option value="1" {{ old('day_of_week') === '1' ? 'selected' : '' }}>Senin (Weekday)</option>
                        <option value="2" {{ old('day_of_week') === '2' ? 'selected' : '' }}>Selasa (Weekday)</option>
                        <option value="3" {{ old('day_of_week') === '3' ? 'selected' : '' }}>Rabu (Weekday)</option>
                        <option value="4" {{ old('day_of_week') === '4' ? 'selected' : '' }}>Kamis (Weekday)</option>
                        <option value="5" {{ old('day_of_week') === '5' ? 'selected' : '' }}>Jumat (Weekday)</option>
                        <option value="6" {{ old('day_of_week') === '6' ? 'selected' : '' }}>Sabtu (Weekend)</option>
                    </select>
                    @error('day_of_week')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Rentang Jam -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="start_time" class="form-label small fw-semibold text-secondary">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" 
                               id="start_time" 
                               name="start_time" 
                               value="{{ old('start_time', '18:00') }}" 
                               class="form-control @error('start_time') is-invalid @enderror" 
                               required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="end_time" class="form-label small fw-semibold text-secondary">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" 
                               id="end_time" 
                               name="end_time" 
                               value="{{ old('end_time', '23:00') }}" 
                               class="form-control @error('end_time') is-invalid @enderror" 
                               required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tarif Khusus -->
                <div class="mb-4">
                    <label for="price_per_hour" class="form-label small fw-semibold text-secondary">Harga Khusus per Jam (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary">Rp</span>
                        <input type="number" 
                               id="price_per_hour" 
                               name="price_per_hour" 
                               value="{{ old('price_per_hour') }}" 
                               class="form-control @error('price_per_hour') is-invalid @enderror" 
                               placeholder="Contoh: 150000" 
                               min="0" 
                               step="1000" 
                               required>
                    </div>
                    @error('price_per_hour')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text small">Tarif ini akan menggantikan harga dasar lapangan saat pemesanan terjadi pada rentang hari dan jam tersebut.</div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.price-rules.index') }}" class="btn btn-light border px-4 py-2 rounded-3 fw-semibold">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-indigo px-4 py-2 rounded-3 fw-bold shadow-sm">
                        Simpan Aturan Tarif
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection