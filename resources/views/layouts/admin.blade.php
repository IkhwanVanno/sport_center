@extends('layouts.app')

@section('body-class', 'overflow-x-hidden bg-light')

@section('layout-content')
<div class="d-flex min-vh-100 overflow-x-hidden">
    <!-- Admin Sidebar Partial -->
    @include('partials.admin-sidebar')

    <!-- Admin Main Content Area -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100 overflow-y-auto">
        <!-- Admin Topbar -->
        <header class="bg-white border-bottom border-light shadow-sm py-3 px-2 px-md-4 d-flex flex-wrap justify-content-between align-items-center gap-2 sticky-top">
            <button class="btn btn-light border d-lg-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#adminSidebar"
                aria-controls="adminSidebar"
                aria-label="Buka menu navigasi">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="d-flex align-items-center gap-2 flex-grow-1">
                <span class="fs-5 fw-bold text-dark text-truncate">
                    @yield('title', 'Admin Panel')
                </span>

                @if(Auth::user()->branch)
                <span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle small d-none d-sm-inline-flex">
                    <i class="bi bi-geo-alt-fill me-1"></i>{{ Auth::user()->branch->name }}
                </span>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-inline">
                    Halo, <strong class="text-dark">{{ Auth::user()->name ?? 'Admin' }}</strong>
                </span>

                <div class="dropdown">
                    <button class="btn btn-sm btn-light border dropdown-toggle rounded-pill px-3 py-1.5 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-shield-lock-fill text-indigo me-1"></i>
                        <span>{{ strtoupper(Auth::user()->role ?? 'ADMIN') }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('home') }}" target="_blank">
                                <i class="bi bi-box-arrow-up-right text-muted"></i>
                                <span>Lihat Website Publik</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 fw-semibold">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Body Content -->
        <main class="flex-grow-1 p-3 p-md-4">
            @include('components.alert')
            @yield('content')
        </main>

        <!-- Admin Footer -->
        <footer class="bg-white border-top py-2 px-4 text-muted small text-center text-md-start d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                © {{ date('Y') }} <strong>Sport Center Management System</strong>
            </div>
            <div>
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
        </footer>
    </div>
</div>
@endsection