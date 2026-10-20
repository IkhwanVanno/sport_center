@extends('layouts.admin')

@section('title', 'Manajemen Akun Pengguna')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header & Action -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h1 class="h3 fw-extrabold text-dark mb-1">Manajemen Akun Pengguna</h1>
            <p class="text-muted small mb-0">Kelola hak akses, penugasan staf cabang (Superadmin, Admin Cabang, Operator), dan akun pelanggan.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-indigo px-4 py-2.5 rounded-3 fw-semibold small shadow-sm d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Pengguna Baru</span>
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card card-custom p-3 p-md-4 border-0 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
            <!-- Filter Role -->
            <div class="col-12 col-sm-6 col-md-3">
                <label for="role" class="form-label small fw-semibold text-secondary">Filter Role</label>
                <select name="role" id="role" class="form-select form-select-sm">
                    <option value="">Semua Role Pengguna</option>
                    <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin Cabang</option>
                    <option value="operator" {{ request('role') === 'operator' ? 'selected' : '' }}>Operator Kasir</option>
                    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                </select>
            </div>

            <!-- Filter Cabang -->
            <div class="col-12 col-sm-6 col-md-3">
                <label for="branch_id" class="form-label small fw-semibold text-secondary">Filter Cabang</label>
                <select name="branch_id" id="branch_id" class="form-select form-select-sm">
                    <option value="">Semua Penugasan Cabang</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ (string) request('branch_id') === (string) $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Keyword -->
            <div class="col-12 col-sm-6 col-md-4">
                <label for="search" class="form-label small fw-semibold text-secondary">Cari Pengguna</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Nama, email, atau no. telepon...">
                </div>
            </div>

            <!-- Filter & Reset Buttons -->
            <div class="col-12 col-sm-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-indigo btn-sm flex-grow-1 fw-semibold py-2">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if(request()->hasAny(['role', 'branch_id', 'search']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm py-2 px-2.5" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="card card-custom border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Kontak Telepon</th>
                        <th>Hak Akses (Role)</th>
                        <th>Penugasan Cabang</th>
                        <th>Terdaftar</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-primary fw-bold" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $user->name }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $user->phone_number ?? '-' }}</span>
                            </td>
                            <td>
                                @if($user->role === 'superadmin')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                        <i class="bi bi-shield-shaded me-1"></i>Superadmin
                                    </span>
                                @elseif($user->role === 'admin')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                        <i class="bi bi-person-gear me-1"></i>Admin Cabang
                                    </span>
                                @elseif($user->role === 'operator')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                        <i class="bi bi-headset me-1"></i>Operator Kasir
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                        <i class="bi bi-person me-1"></i>Customer
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->branch)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $user->branch->name }}
                                    </span>
                                @else
                                    <span class="text-muted small fst-italic">Akses Global / Tanpa Cabang</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <!-- Reset Password Trigger -->
                                    <button type="button" 
                                            class="btn btn-outline-warning btn-sm px-2.5 rounded-pill"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#resetPasswordModal{{ $user->id }}"
                                            title="Reset Password">
                                        <i class="bi bi-key-fill"></i>
                                    </button>

                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>

                                    <!-- Delete Button -->
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-2.5 rounded-pill" title="Hapus Akun">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Reset Password Modal -->
                                <div class="modal fade text-start" id="resetPasswordModal{{ $user->id }}" tabindex="-1" aria-labelledby="resetModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-warning-subtle border-0">
                                                    <h5 class="modal-title fw-bold text-dark" id="resetModalLabel{{ $user->id }}">
                                                        <i class="bi bi-key-fill text-warning me-2"></i>Reset Password: {{ $user->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <p class="text-secondary small mb-3">
                                                        Masukkan password baru untuk akun <strong>{{ $user->email }}</strong>. Pengguna dapat login langsung dengan kredensial baru ini.
                                                    </p>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Password Baru (min. 8 karakter) <span class="text-danger">*</span></label>
                                                        <input type="password" name="password" class="form-control" required minlength="8" placeholder="Masukkan password baru">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small fw-semibold text-secondary">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                                        <input type="password" name="password_confirmation" class="form-control" required minlength="8" placeholder="Ulangi password baru">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 bg-light p-3">
                                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning btn-sm rounded-pill px-4 fw-semibold">Simpan Password Baru</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-5 text-center text-muted">
                                Tidak ada data pengguna yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
