<header x-data="navbar" class="fixed top-0 left-0 right-0 z-sticky transition-all duration-300"
       :class="{ 'bg-white/95 backdrop-blur-xl shadow-soft border-b border-court-100': $store.ui.isScrolled, 'bg-transparent': !$store.ui.isScrolled }">
    <nav class="container-page" aria-label="Navigasi utama">
        <div class="flex h-16 items-center justify-between gap-4">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0" aria-label="Sport Center - Beranda">
                <svg class="w-8 h-8 text-brand-600" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <rect width="32" height="32" rx="8" fill="currentColor"/>
                    <path d="M8 16L14 22L24 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="font-display font-bold text-xl text-court-900">Sport Center</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-1">
                <a href="#features" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-court-600 hover:text-court-900 hover:bg-court-100 transition-colors">Fitur</a>
                <a href="#sports" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-court-600 hover:text-court-900 hover:bg-court-100 transition-colors">Lapangan</a>
                <a href="#how-it-works" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-court-600 hover:text-court-900 hover:bg-court-100 transition-colors">Cara Booking</a>
                <a href="#locations" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-court-600 hover:text-court-900 hover:bg-court-100 transition-colors">Lokasi</a>
            </div>

            <!-- Desktop Actions -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-ghost hidden sm:inline-flex">Dashboard</a>
                    <span class="text-sm font-medium text-court-700">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-primary">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost hidden sm:inline-flex">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar Gratis</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="$store.ui.toggleMobileMenu()"
                    :aria-expanded="$store.ui.mobileMenuOpen"
                    :aria-label="$store.ui.mobileMenuOpen ? 'Tutup menu' : 'Buka menu'"
                    class="md:hidden p-2 rounded-lg text-court-600 hover:bg-court-100 transition-colors">
                <svg x-show="!$store.ui.mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="$store.ui.mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="$store.ui.mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="md:hidden overflow-hidden border-t border-court-100 bg-white animate-slide-down">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" @click="$store.ui.closeMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-court-600 hover:text-court-900 hover:bg-court-100">Fitur</a>
                <a href="#sports" @click="$store.ui.closeMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-court-600 hover:text-court-900 hover:bg-court-100">Lapangan</a>
                <a href="#how-it-works" @click="$store.ui.closeMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-court-600 hover:text-court-900 hover:bg-court-100">Cara Booking</a>
                <a href="#locations" @click="$store.ui.closeMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-court-600 hover:text-court-900 hover:bg-court-100">Lokasi</a>
                <div class="pt-2 border-t border-court-100 flex flex-col gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" @click="$store.ui.closeMobileMenu()" class="btn-secondary w-full justify-center">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" @click="$store.ui.closeMobileMenu()" class="btn-primary w-full justify-center">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" @click="$store.ui.closeMobileMenu()" class="btn-secondary w-full justify-center">Masuk</a>
                        <a href="{{ route('register') }}" @click="$store.ui.closeMobileMenu()" class="btn-primary w-full justify-center">Daftar Gratis</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>