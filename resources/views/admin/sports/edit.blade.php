@extends('layouts.admin')

@section('title', 'Edit Cabang Olahraga - ' . $sport->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Breadcrumb & Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.sports.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Olahraga
            </a>
        </div>

        <div class="card card-custom p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-indigo-subtle text-indigo rounded-3 p-2.5 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        @if($sport->icon && (str_contains($sport->icon, 'bi-') || str_starts_with($sport->icon, 'bi ')))
                            <i class="{{ $sport->icon }} fs-4"></i>
                        @else
                            <i class="bi bi-pencil-square fs-4"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-0">Edit Cabang Olahraga</h4>
                        <p class="text-muted small mb-0">Perbarui informasi cabang olahraga <strong>{{ $sport->name }}</strong>.</p>
                    </div>
                </div>

                <span class="badge bg-light text-secondary border font-monospace small px-2.5 py-1.5">
                    ID #{{ $sport->id }}
                </span>
            </div>

            <form action="{{ route('admin.sports.update', $sport) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Cabang Olahraga -->
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">
                        Nama Cabang Olahraga <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $sport->name) }}" 
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Contoh: Futsal, Badminton, Bola Basket" 
                           maxlength="50"
                           required 
                           autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="form-text small text-muted">
                            Slug saat ini: <code class="text-indigo">{{ $sport->slug }}</code> (otomatis diselaraskan jika nama diubah).
                        </div>
                    @enderror
                </div>

                <!-- Class Icon -->
                <div class="mb-4">
                    <label for="icon" class="form-label small fw-semibold text-secondary">
                        Class Icon (Bootstrap Icons) <span class="text-muted fw-normal">(Opsional)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary">
                            @if($sport->icon && (str_contains($sport->icon, 'bi-') || str_starts_with($sport->icon, 'bi ')))
                                <i class="{{ $sport->icon }}"></i>
                            @else
                                <i class="bi bi-palette"></i>
                            @endif
                        </span>
                        <input type="text" 
                               id="icon" 
                               name="icon" 
                               value="{{ old('icon', $sport->icon) }}" 
                               class="form-control @error('icon') is-invalid @enderror" 
                               placeholder="Contoh: bi bi-dribbble"
                               maxlength="100">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text small text-muted mt-1">
                        Gunakan nama class dari <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener noreferrer" class="text-decoration-none">Bootstrap Icons</a>. Contoh populer: 
                        <code>bi bi-dribbble</code>, <code>bi bi-trophy</code>, <code>bi bi-award</code>, <code>bi bi-activity</code>.
                    </div>
                </div>

                <!-- Informasi Relasi Lapangan Terkait -->
                <div class="p-3 bg-light rounded-3 mb-4 d-flex align-items-center justify-content-between">
                    <div class="small">
                        <i class="bi bi-info-circle text-indigo me-1"></i>
                        <span>Jumlah lapangan terhubung dengan olahraga ini:</span>
                    </div>
                    <span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle fw-semibold">
                        {{ $sport->courts()->count() }} Lapangan
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.sports.index') }}" class="btn btn-light border px-4 py-2 rounded-3 fw-semibold">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-indigo px-4 py-2 rounded-3 fw-bold shadow-sm">
                        <i class="bi bi-check-circle me-1"></i>Perbarui Olahraga
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
