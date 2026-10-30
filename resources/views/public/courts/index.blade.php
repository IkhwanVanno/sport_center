@extends('layouts.public')

@section('title', 'Katalog Lapangan Olahraga')

@section('content')
<div class="container py-4">

    <!-- Page Header & Breadcrumb -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}"
                        class="text-decoration-none text-muted">
                        Beranda
                    </a>
                </li>
                <li class="breadcrumb-item active text-emerald fw-semibold">
                    Katalog Lapangan
                </li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <h1 class="h3 fw-extrabold text-dark mb-1">
                    Daftar Lapangan Olahraga
                </h1>
                <p class="text-muted small mb-0">
                    Temukan lapangan futsal, badminton, basket,
                    dan fasilitas terbaik di berbagai cabang.
                </p>
            </div>

            <div class="text-muted small">
                Total Tersedia:
                <strong class="text-dark">
                    {{ $courts->total() }} Lapangan
                </strong>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="card card-custom p-3 p-md-4 mb-4 border-0 shadow-sm">

        <form method="GET"
            action="{{ route('courts.index') }}"
            class="row g-3 align-items-end">

            <!-- Search -->
            <div class="col-12 col-md-4">
                <label for="search"
                    class="form-label small fw-semibold text-secondary">
                    Cari Nama Lapangan
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>

                    <input type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control border-start-0 ps-0"
                        placeholder="Contoh: Futsal Rumput Sintetis...">
                </div>
            </div>

            <!-- Branch -->
            <div class="col-6 col-md-2">
                <label for="branch_id"
                    class="form-label small fw-semibold text-secondary">
                    Lokasi Cabang
                </label>

                <select name="branch_id" id="branch_id" class="form-select">
                    <option value="">Semua Cabang</option>

                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}"
                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Sport -->
            <div class="col-6 col-md-2">
                <label for="sport_id"
                    class="form-label small fw-semibold text-secondary">
                    Jenis Olahraga
                </label>

                <select name="sport_id" id="sport_id" class="form-select">
                    <option value="">Semua Olahraga</option>

                    @foreach($sports as $sport)
                    <option value="{{ $sport->id }}"
                        {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                        {{ $sport->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Category -->
            <div class="col-6 col-md-2">
                <label for="category"
                    class="form-label small fw-semibold text-secondary">
                    Kategori
                </label>

                <select name="category" id="category" class="form-select">
                    <option value="">Semua Kategori</option>

                    @foreach($categories as $category)
                    <option value="{{ $category }}"
                        {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Capacity -->
            <div class="col-6 col-md-2">
                <label for="capacity"
                    class="form-label small fw-semibold text-secondary">
                    Kapasitas Minimal
                </label>

                <input type="number"
                    name="capacity"
                    id="capacity"
                    min="1"
                    value="{{ request('capacity') }}"
                    class="form-control"
                    placeholder="Contoh: 10">
            </div>

            <!-- Buttons -->
            <div class="col-12 d-flex gap-2">
                <button type="submit"
                    class="btn btn-emerald fw-semibold px-4">
                    <i class="bi bi-funnel me-1"></i>
                    Filter
                </button>

                @if(request()->hasAny([
                'search',
                'branch_id',
                'sport_id',
                'category',
                'capacity'
                ]))
                <a href="{{ route('courts.index') }}"
                    class="btn btn-outline-secondary"
                    title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Courts Grid -->
    <div class="row g-4">

        @forelse($courts as $court)
        <div class="col-12 col-md-6 col-lg-4">
            <x-court-card :court="$court" />
        </div>

        @empty
        <div class="col-12 py-5 text-center">
            <div class="card card-custom p-5 border-0 shadow-sm max-w-md mx-auto">

                <div class="mb-3">
                    <span class="fs-1 text-muted">🔍</span>
                </div>

                <h5 class="fw-bold text-dark mb-1">
                    Lapangan Tidak Ditemukan
                </h5>

                <p class="text-muted small mb-4">
                    Tidak ada lapangan yang sesuai dengan
                    kriteria filter yang Anda pilih.
                </p>

                <div>
                    <a href="{{ route('courts.index') }}"
                        class="btn btn-outline-primary btn-sm px-4 py-2 rounded-pill fw-semibold">
                        Reset Semua Filter
                    </a>
                </div>

            </div>
        </div>
        @endforelse

    </div>

    <!-- Pagination -->
    @if($courts->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $courts->links() }}
    </div>
    @endif

</div>
@endsection