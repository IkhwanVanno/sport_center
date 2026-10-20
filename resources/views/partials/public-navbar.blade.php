<nav class="navbar navbar-expand-lg bg-white border-bottom border-light shadow-sm sticky-top py-3">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 fw-black text-emerald fs-4 tracking-tight" href="{{ route('home') }}">
            <span class="fs-3">⚽</span>
            <span class="fw-bold tracking-wide">SPORT CENTER</span>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links & Actions -->
        <div class="collapse navbar-collapse" id="publicNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('home') ? 'text-emerald active fw-bold' : 'text-secondary' }}" href="{{ route('home') }}">
                        Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('courts.*') ? 'text-emerald active fw-bold' : 'text-secondary' }}" href="{{ route('courts.index') }}">
                        Katalog Lapangan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-secondary" href="{{ route('courts.index') }}#filter-branch">
                        Lokasi Cabang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-secondary" href="{{ route('home') }}#cara-sewa">
                        Cara Sewa
                    </a>
                </li>
            </ul>

            <!-- Auth Buttons -->
            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-6 text-emerald"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                            <li><h6 class="dropdown-header text-uppercase small text-muted">Akun {{ strtoupper(Auth::user()->role) }}</h6></li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 text-indigo"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            @if(Auth::user()->isCustomer())
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('user.dashboard') }}">
                                        <i class="bi bi-ticket-perforated text-emerald"></i>
                                        <span>Booking Saya</span>
                                    </a>
                                </li>
                            @endif
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
                @else
                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-secondary fw-semibold px-3 py-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-emerald btn-sm rounded-pill px-4 py-2 shadow-sm">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>