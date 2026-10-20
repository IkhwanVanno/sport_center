<header class="bg-white border-bottom border-light shadow-sm sticky-top py-2 px-3 px-md-4">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Brand & Branch Info -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('operator.checkin.index') }}" class="d-flex align-items-center gap-2 text-decoration-none fw-bold text-dark fs-6">
                <span class="fs-4">⚽</span>
                <span class="d-none d-sm-inline tracking-wide">SPORT CENTER</span>
                <span class="badge bg-indigo text-white ms-1 small">KASIR / OPERATOR</span>
            </a>

            <div class="vr d-none d-md-block my-1 text-muted"></div>

            <div class="d-none d-md-flex align-items-center gap-1 text-muted small">
                <i class="bi bi-geo-alt-fill text-danger"></i>
                <span>Lokasi:</span>
                <span class="fw-bold text-dark">{{ Auth::user()->branch->name ?? 'Cabang Pusat MERR Surabaya' }}</span>
            </div>
        </div>

        <!-- Quick Operational Nav & User Profile -->
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <nav class="d-flex align-items-center gap-1">
                <a href="{{ route('operator.checkin.index') }}" class="btn btn-sm {{ request()->routeIs('operator.checkin.*') ? 'btn-indigo' : 'btn-outline-secondary' }} rounded-pill px-3">
                    <i class="bi bi-qr-code-scan me-1"></i>
                    <span class="d-none d-sm-inline">Check-In</span>
                </a>
                <a href="{{ route('operator.walkin.index') }}" class="btn btn-sm {{ request()->routeIs('operator.walkin.*') ? 'btn-emerald' : 'btn-outline-secondary' }} rounded-pill px-3">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span class="d-none d-sm-inline">Walk-In</span>
                </a>
                <a href="{{ route('operator.schedule.index') }}" class="btn btn-sm {{ request()->routeIs('operator.schedule.*') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                    <i class="bi bi-calendar3 me-1"></i>
                    <span class="d-none d-sm-inline">Jadwal</span>
                </a>
            </nav>

            <div class="vr my-1 text-muted"></div>

            <!-- Profile & Logout -->
            <div class="dropdown">
                <button class="btn btn-sm btn-light border dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-badge text-indigo fs-6"></i>
                    <span class="fw-semibold small d-none d-sm-inline">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                    <li><h6 class="dropdown-header text-uppercase small text-muted">Petugas Lapangan</h6></li>
                    <li><span class="dropdown-item-text small text-muted">{{ Auth::user()->email }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 fw-semibold">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar / Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
