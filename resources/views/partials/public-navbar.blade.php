<header class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Logo & Brand -->
        <a href="{{ route('home') }}" class="font-black text-xl text-emerald-600 tracking-tight flex items-center gap-2">
            <span>⚽</span> SPORT CENTER
        </a>

        <!-- Public Navigation Links -->
        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 transition {{ request()->routeIs('home') ? 'text-emerald-600 font-bold' : '' }}">
                Beranda
            </a>
            <a href="#" class="hover:text-emerald-600 transition">
                Cari Lapangan
            </a>
            <a href="#cabang" class="hover:text-emerald-600 transition">
                Lokasi Cabang
            </a>
            <a href="#cara-sewa" class="hover:text-emerald-600 transition">
                Cara Sewa
            </a>
        </nav>

        <!-- Auth Navigation Actions -->
        <div class="flex items-center gap-3">
            @auth
                <!-- Jika User Sudah Login -->
                <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                    Dashboard Saya &rarr;
                </a>
            @else
                <!-- Jika User Belum Login / Guest -->
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 text-xs font-bold px-3 py-2 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                    Daftar Akun
                </a>
            @endauth
        </div>
    </div>
</header>