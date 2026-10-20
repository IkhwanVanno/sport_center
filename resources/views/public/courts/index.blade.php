@extends('layouts.public')

@section('title', 'Katalog Lapangan Olahraga')

@section('content')
<div class="container py-4">

    <!-- Page Header & Breadcrumb -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-emerald fw-semibold" aria-current="page">Katalog Lapangan</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <h1 class="h3 fw-extrabold text-dark mb-1">Daftar Lapangan Olahraga</h1>
                <p class="text-muted small mb-0">Temukan lapangan futsal, badminton, basket, dan fasilitas terbaik di berbagai cabang.</p>
            </div>
            <div class="text-muted small">
                Total Tersedia: <strong class="text-dark">{{ $courts->count() }} Lapangan</strong>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="card card-custom p-3 p-md-4 mb-4 border-0 shadow-sm">
        <form method="GET" action="{{ route('courts.index') }}" class="row g-3 align-items-end">
            <!-- Search Keyword -->
            <div class="col-12 col-md-4">
                <label for="search" class="form-label small fw-semibold text-secondary">Cari Nama Lapangan</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="form-control border-start-0 ps-0" 
                           placeholder="Contoh: Futsal Rumput Sintetis...">
                </div>
            </div>

            <!-- Filter Cabang -->
            <div class="col-6 col-md-3" id="filter-branch">
                <label for="branch_id" class="form-label small fw-semibold text-secondary">Lokasi Cabang</label>
                <select name="branch_id" id="branch_id" class="form-select">
                    <option value="">Semua Cabang</option>
                    @foreach($courts->pluck('branch')->unique('id')->filter() as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Jenis Olahraga -->
            <div class="col-6 col-md-3">
                <label for="sport_id" class="form-label small fw-semibold text-secondary">Jenis Olahraga</label>
                <select name="sport_id" id="sport_id" class="form-select">
                    <option value="">Semua Olahraga</option>
                    @foreach($courts->pluck('sport')->unique('id')->filter() as $sport)
                        <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                            {{ $sport->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button & Reset -->
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-emerald w-100 fw-semibold py-2">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'sport_id']))
                    <a href="{{ route('courts.index') }}" class="btn btn-outline-secondary py-2" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @php
        // Filter collection if query params present
        $filteredCourts = $courts;
        if (request('search')) {
            $keyword = strtolower(request('search'));
            $filteredCourts = $filteredCourts->filter(fn($c) => str_contains(strtolower($c->name), $keyword));
        }
        if (request('branch_id')) {
            $filteredCourts = $filteredCourts->where('branch_id', request('branch_id'));
        }
        if (request('sport_id')) {
            $filteredCourts = $filteredCourts->where('sport_id', request('sport_id'));
        }
    @endphp

    <!-- Courts Grid -->
    <div class="row g-4">
        @forelse($filteredCourts as $court)
            <div class="col-12 col-md-6 col-lg-4">
                <x-court-card :court="$court" />
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="card card-custom p-5 border-0 shadow-sm max-w-md mx-auto">
                    <div class="mb-3">
                        <span class="fs-1 text-muted">🔍</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Lapangan Tidak Ditemukan</h5>
                    <p class="text-muted small mb-4">Tidak ada lapangan yang sesuai dengan kriteria filter yang Anda pilih.</p>
                    <div>
                        <a href="{{ route('courts.index') }}" class="btn btn-outline-primary btn-sm px-4 py-2 rounded-pill fw-semibold">
                            Reset Semua Filter
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
