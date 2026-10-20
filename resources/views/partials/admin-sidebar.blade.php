<aside class="app-sidebar shadow-sm">
    <div>
        <!-- Brand / Header -->
        <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center gap-2 fw-bold fs-6">
                <span class="fs-4">⚽</span>
                <span>SPORT CENTER</span>
            </a>
            <span class="badge bg-secondary-subtle text-secondary small" style="font-size: 0.65rem;">ADMIN</span>
        </div>

        <!-- Navigation Links -->
        <nav class="nav flex-column p-2 mt-2 gap-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-heading">Master Data</div>

            <a href="{{ route('admin.branches.index') }}" class="nav-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Cabang</span>
            </a>

            <a href="{{ route('admin.courts.index') }}" class="nav-link {{ request()->routeIs('admin.courts.*') ? 'active' : '' }}">
                <i class="bi bi-trophy"></i>
                <span>Lapangan</span>
            </a>

            <a href="{{ route('admin.price-rules.index') }}" class="nav-link {{ request()->routeIs('admin.price-rules.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Aturan Tarif</span>
            </a>

            <div class="sidebar-heading">Transaksi</div>

            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Pesanan & Booking</span>
            </a>

            <div class="sidebar-heading">Laporan</div>

            <a href="{{ route('admin.reports.revenue') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Laporan Pendapatan</span>
            </a>
        </nav>
    </div>

    <!-- User / Footer Section -->
    <div class="p-3 border-top border-secondary border-opacity-25">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 text-truncate">
                <i class="bi bi-person-circle fs-4 text-emerald"></i>
                <div class="text-truncate">
                    <div class="small fw-bold text-white text-truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
                    <div class="text-secondary" style="font-size: 0.72rem;">{{ strtoupper(Auth::user()->role ?? 'ADMIN') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-danger p-1 text-decoration-none" title="Logout">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </form>
        </div>
    </div>
</aside>