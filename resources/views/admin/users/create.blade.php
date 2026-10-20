@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Breadcrumb & Back -->
        <div class="mb-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Pengguna
            </a>
        </div>

        <div class="card card-custom p-4 border-0 shadow-sm">
            <h4 class="fw-bold text-dark border-bottom pb-3 mb-4">Tambah Pengguna / Staf Baru</h4>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Contoh: Budi Santoso"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="user@sportcenter.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label small fw-semibold text-secondary">Nomor WhatsApp / HP</label>
                        <input type="text"
                               id="phone_number"
                               name="phone_number"
                               value="{{ old('phone_number') }}"
                               class="form-control @error('phone_number') is-invalid @enderror"
                               placeholder="081234567890">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <!-- Role -->
                    <div class="col-md-6">
                        <label for="role" class="form-label small fw-semibold text-secondary">Role / Hak Akses <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required onchange="toggleBranchField(this.value)">
                            <option value="">-- Pilih Role --</option>
                            <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Superadmin (Akses Global Seluruh Cabang)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin Cabang (Restricted 1 Cabang)</option>
                            <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>Operator Kasir (Check-in & Walk-in)</option>
                            <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer (Pelanggan Umum)</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Penugasan Cabang -->
                    <div class="col-md-6" id="branchContainer">
                        <label for="branch_id" class="form-label small fw-semibold text-secondary">
                            Penugasan Cabang <span id="branchRequiredStar" class="text-danger d-none">*</span>
                        </label>
                        <select name="branch_id" id="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                            <option value="">-- Tanpa Cabang / Bebas --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="form-text text-muted small" id="branchHelperText">
                            Wajib dipilih jika role adalah Admin Cabang atau Operator.
                        </span>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold text-secondary">Password Baru (min. 8 karakter) <span class="text-danger">*</span></label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Masukkan password akun baru"
                           required
                           minlength="8">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-semibold">Batal</a>
                    <button type="submit" class="btn btn-indigo px-4 py-2 rounded-pill fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i>Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleBranchField(role) {
        const star = document.getElementById('branchRequiredStar');
        const branchSelect = document.getElementById('branch_id');
        const helper = document.getElementById('branchHelperText');

        if (role === 'admin' || role === 'operator') {
            star.classList.remove('d-none');
            branchSelect.setAttribute('required', 'required');
            helper.classList.remove('text-muted');
            helper.classList.add('text-primary');
        } else {
            star.classList.add('d-none');
            branchSelect.removeAttribute('required');
            helper.classList.add('text-muted');
            helper.classList.remove('text-primary');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect) {
            toggleBranchField(roleSelect.value);
        }
    });
</script>
@endsection
