@extends('layouts.app')

@section('body-class', 'd-flex flex-column min-vh-100 bg-light')

@section('layout-content')
    <!-- Customer Navbar -->
    <header class="navbar navbar-expand-lg bg-white border-bottom border-light shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-black text-emerald fs-5 tracking-tight" href="{{ route('home') }}">
                <span class="fs-4">⚽</span>
                <span class="fw-bold tracking-wide">SPORT CENTER</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#customerNavbar" aria-controls="customerNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="customerNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('courts.*') ? 'text-emerald fw-bold' : 'text-secondary' }}" href="{{ route('courts.index') }}">
                            <i class="bi bi-search me-1"></i>Sewa Lapangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('user.dashboard') ? 'text-emerald fw-bold' : 'text-secondary' }}" href="{{ route('user.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('user.bookings.*') ? 'text-emerald fw-bold' : 'text-secondary' }}" href="{{ route('user.bookings.index') }}">
                            <i class="bi bi-receipt me-1"></i>Riwayat Booking
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-6 text-emerald"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                            <li><h6 class="dropdown-header text-uppercase small text-muted">Akun Pelanggan</h6></li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('user.profile') }}">
                                    <i class="bi bi-person-gear text-indigo"></i>
                                    <span>Profil & Keamanan</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('user.bookings.index') }}">
                                    <i class="bi bi-ticket-perforated text-emerald"></i>
                                    <span>Semua Booking</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2 fw-semibold">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Keluar (Logout)</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1 py-4">
        <div class="container">
            @include('components.alert')
            @yield('content')
        </div>
    </main>

    <!-- Customer Footer -->
    <footer class="bg-white border-top py-3 text-center text-muted small no-print">
        <div class="container">
            © {{ date('Y') }} <strong>Sport Center</strong>. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </footer>
@endsection